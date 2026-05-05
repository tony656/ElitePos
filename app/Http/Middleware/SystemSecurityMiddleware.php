<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\systemModel;
use Symfony\Component\HttpFoundation\Response;

class SystemSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $system = systemModel::first();
        
        // Check if system is shut down
        if ($system && $system->system_shutdown) {
            // Allow emergency login route (bypasses shutdown)
            if ($request->is('admin/emergency-login') ||
                $request->is('admin/emergency-login/*')) {
                return $next($request);
            }
            
            // Allow access to authentication routes, assets, and face recognition
            if ($request->is('login') ||
                $request->is('logout') ||
                $request->is('signout') ||
                $request->is('assets/*') ||
                $request->is('css/*') ||
                $request->is('js/*') ||
                $request->is('images/*') ||
                $request->is('face/*')) {
                return $next($request);
            }
            
            // Check if user has valid emergency access
            if (Auth::check() && session('emergency_access') && !session('emergency_expires_at')) {
                // Emergency access expired, clear it
                session()->forget(['emergency_access', 'emergency_login_at', 'emergency_expires_at']);
            }
            
            if (Auth::check() && session('emergency_access') && session('emergency_expires_at')) {
                $expiresAt = \Carbon\Carbon::parse(session('emergency_expires_at'));
                if (now()->lessThan($expiresAt)) {
                    // User has valid emergency access, allow through
                    return $next($request);
                } else {
                    // Emergency access expired
                    session()->forget(['emergency_access', 'emergency_login_at', 'emergency_expires_at']);
                }
            }
            
            // If user is not authenticated, handle based on request type
            if (!Auth::check()) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message' => 'The system is currently shut down. Emergency access required.',
                        'system_shutdown' => true,
                        'requires_emergency' => true
                    ], 503);
                }
                return redirect()->route('admin.emergency.login')
                    ->with('error', 'The system is currently shut down. Emergency access required.');
            }
            
            // If user is authenticated but no emergency access, handle based on request type
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'The system has been shut down. Emergency access required to continue.',
                    'system_shutdown' => true,
                    'requires_emergency' => true
                ], 503);
            }
            
            // For web requests, show shutdown page
            return response()->view('system-shutdown', [
                'message' => 'The system has been shut down by the administrator. Emergency access required to continue.'
            ], 503);
        }
        
        // Check if sign-ins are blocked (only for login attempts)
        if ($system && $system->block_signins) {
            // Allow emergency login route (bypasses block)
            if ($request->is('admin/emergency-login') ||
                $request->is('admin/emergency-login/*')) {
                return $next($request);
            }
            
            // Only intercept login attempts
            if ($request->is('login') || $request->is('user/login') || $request->is('admin/login')) {
                // Check if it's a POST request (actual login attempt)
                if ($request->isMethod('post')) {
                    // For API/JSON requests, return JSON error
                    if ($request->expectsJson() || $request->is('api/*')) {
                        return response()->json([
                            'message' => 'All sign-ins are currently blocked by the system administrator.',
                            'blocked' => true
                        ], 403);
                    }
                    
                    return redirect()->back()
                        ->with('error', 'All sign-ins are currently blocked by the system administrator. Please try again later.');
                }
            }
        }
        
        return $next($request);
    }
}