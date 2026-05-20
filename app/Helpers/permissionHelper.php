<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('canUser')) {
    function canUser(string $permission): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        // Admin users have all permissions (case-insensitive)
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return true;
        }

        // permissions column contains JSON array
        $permissions = $user->permissions;

        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true);
        }

        return is_array($permissions) && in_array($permission, $permissions);
    }
}
