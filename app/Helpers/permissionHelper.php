<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionHelper
{
    /**
     * Get user permissions from database
     */
    public static function getUserPermissions($userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        $permissions = DB::table('user_permissions')
            ->where('user_id', $userId)
            ->pluck('permission')
            ->toArray();
        
        return $permissions;
    }
    
    /**
     * Check if current user has specific permission
     */
    public static function can($permission)
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        
        if (in_array($user->levelStatus, ['Admin2', 'Admin', 'admin2', 'admin'])) {
            return true;
        }
        
        $permissions = self::getUserPermissions($user->id);
        return in_array($permission, $permissions);
    }
    
    /**
     * Save user permissions
     */
    public static function savePermissions($userId, array $permissions)
    {
        // Delete existing permissions
        DB::table('user_permissions')->where('user_id', $userId)->delete();
        
        // Insert new permissions
        $data = [];
        $uniquePermissions = array_unique($permissions);
        foreach ($uniquePermissions as $permission) {
            $data[] = [
                'user_id' => $userId,
                'permission' => $permission,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        if (!empty($data)) {
            DB::table('user_permissions')->insert($data);
        }
    }
}