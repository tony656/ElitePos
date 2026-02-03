<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('canUser')) {
    function canUser(string $permission): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        // permissions column contains JSON array
        $permissions = $user->permissions;

        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true);
        }

        return is_array($permissions) && in_array($permission, $permissions);
    }
}
