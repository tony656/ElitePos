<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\UserFaceEncoding;
use App\Models\ActiveSession;
use App\Models\logModal;
use Carbon\Carbon;

class FaceRecognitionController extends Controller
{
    /**
     * Show face registration page
     */
    public function showRegistrationPage()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Get existing face encodings
        $encodings = UserFaceEncoding::where('user_id', $user->id)
            ->orderBy('registered_at', 'desc')
            ->get();

        // Check if user is admin or regular user
        $isAdmin = strtolower(trim($user->levelStatus)) === 'admin';
        
        if ($isAdmin) {
            // Get all users for selection dropdown (excluding admins if needed)
            $users = \App\Models\User::where('levelStatus', '!=', 'admin')
                ->orWhere('levelStatus', null)
                ->orderBy('name', 'asc')
                ->get(['id', 'name', 'email', 'levelStatus']);
            
            return view('admin.face-registration', compact('encodings', 'users'));
        }
        
        return view('user.face-registration', compact('encodings'));
    }

    /**
     * Show face verification page
     */
    public function showVerificationPage()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        return view('user.face-verification');
    }

    /**
     * Register a face encoding for a user
     */
    public function register(Request $request)
    {
        $adminUser = Auth::user();
        
        if (!$adminUser) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $validated = $request->validate([
                'face_encoding' => 'required|array',
                'user_id' => 'nullable|integer|exists:users,id',
                'device_name' => 'nullable|string',
                'browser' => 'nullable|string',
                'os' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors as JSON instead of redirect
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        $encoding = $validated['face_encoding'];

        try {
            // Validate face encoding data
            $encoding = $request->face_encoding;
            if (!is_array($encoding) || count($encoding) < 128) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid face encoding data'
                ], 400);
            }

            // Determine which user to assign the face to
            $isAdmin = strtolower(trim($adminUser->levelStatus)) === 'admin';
            $userId = $request->user_id;
            
            if ($isAdmin && $userId) {
                // Admin registering face for another user
                $targetUser = \App\Models\User::find($userId);
                if (!$targetUser) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected user not found'
                    ], 404);
                }
                $userId = $targetUser->id;
            } else {
                // Regular user or admin without selection - use authenticated user
                $userId = $adminUser->id;
            }

            // Store the face encoding
            $faceEncoding = UserFaceEncoding::create([
                'user_id' => $userId,
                'face_encoding' => $encoding,
                'device_name' => $request->device_name,
                'browser' => $request->browser,
                'os' => $request->os,
                'ip_address' => $request->ip(),
                'registered_at' => now(),
                'is_active' => true
            ]);

            // Log the registration (always log the admin who performed the action)
            try {
                logModal::create([
                    'title' => 'Face Registration',
                    'description' => 'Face encoding registered by ' . (session('username') ?? $adminUser->name) . ' for user ID: ' . $userId . ' from ' . ($request->device_name ?? 'unknown device'),
                    'user' => session('username') ?? $adminUser->name,
                    'status' => 'done'
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to create log: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Face registered successfully',
                'data' => [
                    'id' => $faceEncoding->id,
                    'registered_at' => $faceEncoding->registered_at
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Face registration error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to register face: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify a face against stored encodings
     */
    public function verify(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $validated = $request->validate([
                'face_encoding' => 'required|array',
                'session_id' => 'required|string'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors as JSON instead of redirect
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            // Get active face encodings for this user
            $encodings = UserFaceEncoding::where('user_id', $user->id)
                ->where('is_active', true)
                ->get();

            if ($encodings->isEmpty()) {
                // No face registered - log and return failure
                $this->logVerification($user->id, $request->session_id, false, 0, 'No face encoding registered for this user');
                
                return response()->json([
                    'success' => false,
                    'message' => 'No face data registered. Please register your face first.',
                    'requires_registration' => true
                ], 403);
            }

            // Compare face encoding with stored encodings
            $bestMatch = null;
            $highestConfidence = 0;
            $threshold = 0.6; // Confidence threshold (60% match required)

            foreach ($encodings as $encoding) {
                $confidence = $this->compareFaceEncodings($request->face_encoding, $encoding->face_encoding);
                
                if ($confidence > $highestConfidence) {
                    $highestConfidence = $confidence;
                    $bestMatch = $encoding;
                }
            }

            $isVerified = $highestConfidence >= $threshold;

            // Log the verification attempt
            $this->logVerification(
                $user->id,
                $request->session_id,
                $isVerified,
                $highestConfidence * 100,
                $isVerified ? null : 'Face did not match'
            );

            if ($isVerified) {
                // Update session to mark face as verified
                $session = ActiveSession::where('session_id', $request->session_id)->first();
                if ($session) {
                    $session->face_verified = true;
                    $session->last_face_check = now();
                    $session->face_verification_expires_at = now()->addMinutes(30); // Valid for 30 minutes
                    $session->save();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Face verified successfully',
                    'confidence' => round($highestConfidence * 100, 2)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Face verification failed. Please position your face clearly.',
                    'confidence' => round($highestConfidence * 100, 2)
                ], 403);
            }

        } catch (\Exception $e) {
            Log::error('Face verification error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Verification error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Compare two face encodings and return confidence score
     * Using a simple Euclidean distance-based comparison
     */
    private function compareFaceEncodings($encoding1, $encoding2)
    {
        if (count($encoding1) !== count($encoding2)) {
            return 0;
        }

        $sumSquared = 0;
        for ($i = 0; $i < count($encoding1); $i++) {
            $diff = $encoding1[$i] - $encoding2[$i];
            $sumSquared += $diff * $diff;
        }

        $distance = sqrt($sumSquared);
        
        // Convert distance to confidence (0-1 scale)
        // Lower distance = higher confidence
        // Typical threshold for face recognition is around 0.6
        $confidence = max(0, 1 - ($distance / 0.8));
        
        return $confidence;
    }

    /**
     * Log face verification attempt
     */
    private function logVerification($userId, $sessionId, $success, $confidence, $error = null)
    {
        try {
            \DB::table('face_verification_logs')->insert([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'verification_success' => $success,
                'confidence' => $confidence,
                'error_message' => $error,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log face verification: ' . $e->getMessage());
        }
    }

    /**
     * Get user's registered face encodings
     */
    public function getEncodings(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $isAdmin = strtolower(trim($user->levelStatus)) === 'admin';
        $userId = $user->id;
        
        // If admin and user_id parameter provided, use that user's encodings
        if ($isAdmin && $request->has('user_id')) {
            $userId = (int) $request->user_id;
            // Verify the user exists and is not an admin (optional security check)
            $targetUser = \App\Models\User::find($userId);
            if (!$targetUser || strtolower(trim($targetUser->levelStatus)) === 'admin') {
                return response()->json(['success' => false, 'message' => 'Invalid user'], 403);
            }
        }

        $encodings = UserFaceEncoding::where('user_id', $userId)
            ->orderBy('registered_at', 'desc')
            ->get(['id', 'device_name', 'browser', 'os', 'ip_address', 'registered_at', 'is_active']);

        return response()->json([
            'success' => true,
            'data' => $encodings
        ]);
    }

    /**
     * Delete a face encoding
     */
    public function deleteEncoding(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $encoding = UserFaceEncoding::where('id', $id)->where('user_id', $user->id)->first();
        
        if (!$encoding) {
            return response()->json(['success' => false, 'message' => 'Encoding not found'], 404);
        }

        $encoding->delete();

        // Log the deletion
        logModal::create([
            'title' => 'Face Encoding Deleted',
            'description' => 'Face encoding deleted by ' . session('username'),
            'user' => session('username'),
            'status' => 'done'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Face encoding deleted successfully'
        ]);
    }

    /**
     * Get face verification logs for current user
     */
    public function getVerificationLogs(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $logs = \DB::table('face_verification_logs')
            ->where('user_id', $user->id)
            ->orderBy('verified_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
}