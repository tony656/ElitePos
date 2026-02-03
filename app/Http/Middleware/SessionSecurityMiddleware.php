<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActiveSession;
use App\Services\DeviceDetectionService;

class SessionSecurityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $sessionId = session()->getId();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        $userId = session('user_id') ?? auth()->id();

        $deviceService = new DeviceDetectionService();
        $deviceInfo = $deviceService->getDeviceInfo($userAgent);

        $session = ActiveSession::where('session_id', $sessionId)->first();

        if (!$session) {
            $session = ActiveSession::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'device_name' => $deviceService->getDeviceName($userAgent),
                'browser' => $deviceInfo['browser'],
                'os' => $deviceInfo['os'],
                'device_type' => $deviceInfo['device_type'],
                'is_authorized' => auth()->check(),
                'status' => auth()->check() ? 'active' : 'suspicious',
                'last_activity' => now(),
            ]);
        } else {
            // Check for suspicious activity
            if ($session->ip_address !== $ipAddress || $session->user_agent !== $userAgent) {
                $session->update([
                    'status' => 'suspicious',
                    'is_authorized' => false,
                ]);
            }

            $session->update(['last_activity' => now()]);
        }

        // Block access if session is blocked
        if ($session->is_blocked) {
            abort(403, 'Access denied. Your session has been blocked.');
        }

        return $next($request);
    }
}
