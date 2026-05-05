<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ActiveSession;
use App\Models\UserFaceEncoding;
use Carbon\Carbon;

class FaceRecognitionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Skip if user is not authenticated
        if (!$user) {
            \Log::info('FaceRecognitionMiddleware: No user, skipping');
            return $next($request);
        }

        // Get system settings
        $system = \App\Models\systemModel::first();
        $faceRecognitionEnabled = $system && $system->face_recognition_enabled;
        
        \Log::info('FaceRecognitionMiddleware check', [
            'user' => $user->name ?? 'unknown',
            'path' => $request->path(),
            'face_enabled' => $faceRecognitionEnabled
        ]);
        
        // Skip if face recognition is disabled globally
        if (!$faceRecognitionEnabled) {
            \Log::info('FaceRecognitionMiddleware: Disabled globally, skipping');
            return $next($request);
        }

        // Check if face verification is required for this request
        // URI paths that should bypass face verification
        // NOTE: These must be checked BEFORE session verification to avoid redirect loops
        $excludedPaths = [
            'face/verify',
            'face/verify-page',
            'face/register',
            'face/encodings',
            'face/encoding/*',
            'face/logs',
            'logout',
            'signout',
            'login',
            'admin/emergency-login',
            'admin/emergency-login/*',
            'assets/*',
            'css/*',
            'js/*',
            'images/*',
            'admin/toggle-face-recognition',
            'admin/toggle-block-signins',
            'admin/toggle-system-shutdown',
            'toggleFaceRecognition',
            'admin/security',
            'security',
            'admin/home',
            'admin/dashboard',
            'home',
            'dashboard',
            'admin/face/register',
            'admin/face/verify',
            'admin/face/verify-page',
            'admin/face/encodings',
            'admin/face/encoding/*',
            'admin/face/logs',
            'user/face/register',
            'user/face/verify',
            'user/face/verify-page',
            'user/face/encodings',
            'user/face/encoding/*',
            'user/face/logs',
            'api/system-status',
        ];

        $isExcluded = false;
        foreach ($excludedPaths as $path) {
            if ($request->is($path)) {
                $isExcluded = true;
                break;
            }
        }

        \Log::info('FaceRecognitionMiddleware: Exclusion check', [
            'path' => $request->path(),
            'is_excluded' => $isExcluded,
            'face_enabled' => $faceRecognitionEnabled,
            'user_id' => $user->id,
            'session_id' => session()->getId()
        ]);

        // Skip verification for excluded routes
        if ($isExcluded) {
            \Log::info('FaceRecognitionMiddleware: Route excluded, skipping');
            return $next($request);
        }

        // Get current session
        $sessionId = session()->getId();
        $session = ActiveSession::where('session_id', $sessionId)->first();
        
        // If no session record exists, create one
        if (!$session) {
            $session = ActiveSession::create([
                'user_id' => $user->id,
                'session_id' => $sessionId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_name' => $this->getDeviceName($request->userAgent()),
                'browser' => $this->getBrowser($request->userAgent()),
                'os' => $this->getOS($request->userAgent()),
                'last_activity' => now(),
                'face_verified' => false,
                'failed_face_attempts' => 0
            ]);
        }

        // Check if session is already verified and not expired
        $verificationTimeout = $system->face_verification_timeout ?? 5;
        
        if ($session->face_verified && $session->face_verification_expires_at) {
            if (Carbon::now()->lt($session->face_verification_expires_at)) {
                // Still verified, extend the session
                $session->last_activity = now();
                $session->save();
                return $next($request);
            }
        }

        // Check if user has any face encodings registered
        $hasFaceEncodings = UserFaceEncoding::where('user_id', $user->id)->where('is_active', true)->exists();
        
        if (!$hasFaceEncodings) {
            // User hasn't registered face - log and return failure
            Log::warning('User attempting to access without face registration', [
                'user_id' => $user->id,
                'username' => session('username')
            ]);
            
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Face registration required',
                    'requires_face_registration' => true
                ], 403);
            }
            
            // Redirect to appropriate registration page based on user role
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                return redirect()->route('face.register.page')
                    ->with('warning', 'You must register your face before accessing the system when face recognition is enabled.');
            }
            return redirect()->route('face.register.page')
                ->with('warning', 'You must register your face before accessing the system when face recognition is enabled.');
        }

        // Face verification required - redirect to verification page or return JSON error
        // For API/JSON requests, return proper JSON response
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Face verification required',
                'requires_verification' => true,
                'session_id' => $sessionId
            ], 403);
        }

        // For web requests, redirect to verification page
        \Log::info('FaceRecognitionMiddleware: Redirecting to verification page', [
            'user_id' => $user->id,
            'path' => $request->path(),
            'has_encodings' => $hasFaceEncodings,
            'session_verified' => $session->face_verified ?? false
        ]);
        
        // Check if we're already trying to go to verification page to avoid loop
        if ($request->is('face/verify') || $request->is('face/verify-page')) {
            \Log::warning('FaceRecognitionMiddleware: Already on verification page, allowing through to avoid loop');
            return $next($request);
        }
        
        return redirect()->route('face.verify.page');
    }

    /**
     * Get device name from user agent
     */
    private function getDeviceName($userAgent)
    {
        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'mobile') !== false || strpos($userAgent, 'android') !== false) {
            return 'Mobile';
        }
        
        if (strpos($userAgent, 'tablet') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'Tablet';
        }
        
        return 'Desktop';
    }

    /**
     * Get browser from user agent
     */
    private function getBrowser($userAgent)
    {
        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'chrome') !== false) {
            return 'Chrome';
        }
        
        if (strpos($userAgent, 'firefox') !== false) {
            return 'Firefox';
        }
        
        if (strpos($userAgent, 'safari') !== false) {
            return 'Safari';
        }
        
        if (strpos($userAgent, 'edge') !== false) {
            return 'Edge';
        }
        
        return 'Unknown';
    }

    /**
     * Get OS from user agent
     */
    private function getOS($userAgent)
    {
        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'windows') !== false) {
            return 'Windows';
        }
        
        if (strpos($userAgent, 'mac os') !== false) {
            return 'MacOS';
        }
        
        if (strpos($userAgent, 'linux') !== false) {
            return 'Linux';
        }
        
        if (strpos($userAgent, 'android') !== false) {
            return 'Android';
        }
        
        if (strpos($userAgent, 'ios') !== false || strpos($userAgent, 'iphone') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'iOS';
        }
        
        return 'Unknown';
    }
}