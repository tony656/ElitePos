<?php

namespace App\Services;

use Agent;

class DeviceDetectionService
{
    public function getDeviceInfo($userAgent)
    {
        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent($userAgent);

        return [
            'device_name' => $agent->device(),
            'browser' => $agent->browser(),
            'os' => $agent->platform(),
            'device_type' => $this->getDeviceType($agent),
            'is_mobile' => $agent->isMobile(),
            'is_tablet' => $agent->isTablet(),
            'is_desktop' => $agent->isDesktop(),
        ];
    }

    private function getDeviceType($agent)
    {
        if ($agent->isDesktop()) return 'Desktop';
        if ($agent->isTablet()) return 'Tablet';
        if ($agent->isMobile()) return 'Mobile';
        return 'Unknown';
    }

    public function getDeviceName($userAgent)
    {
        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent($userAgent);
        
        $device = $agent->device() ?? 'Unknown Device';
        $browser = $agent->browser() ?? 'Unknown Browser';
        
        return "{$device} - {$browser}";
    }
}