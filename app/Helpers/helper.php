<?php

use App\Models\accountModel;

if (! function_exists('isAdmin')) {
    function isAdmin()
    {
        if (Auth::check() && Auth::user()->levelStatus === 'Admin') {
            return true;
        } else {
            return false;
        }
    }
}

if (! function_exists('formatNumber')) {
    function formatNumber($num)
    {
        if ($num >= 1000000) {
            return number_format($num / 1000000, 1).'m';
        } elseif ($num >= 1000) {
            return number_format($num / 1000, 1).'k';
        }

        return $num;
    }
}

if (! function_exists('getSessionAccountId')) {
    /**
     * Get the account ID from the session
     * Returns the numeric account ID stored in session
     * Returns null if not set
     *
     * @deprecated Use getCurrentShopId() instead to respect selected_shop_id
     */
    function getSessionAccountId()
    {
        return session('account_id');
    }
}

 if (! function_exists('getCurrentShopId')) {
     /**
      * Get the current shop ID from explicit shop selection only
      * Returns null if no shop has been explicitly selected
      */
     function getCurrentShopId()
     {
         return session('selected_shop_id');
     }
 }

if (! function_exists('getSessionAccountName')) {
    /**
     * Get the account name from the session
     * Returns the account name (string) stored in session
     * Returns null if not set
     */
    function getSessionAccountName()
    {
        return session('account');
    }
}

if (! function_exists('user_accounts')) {
    function getUserAccounts(): array
    {
        $accounts = session('accessible_accounts');
        if (is_array($accounts) && ! empty($accounts)) {
            return $accounts;
        }

        if (Auth::check() && Auth::user()->account) {
            $account = accountModel::find(Auth::user()->account);
            if ($account) {
                return [
                    [
                        'id' => $account->id,
                        'name' => $account->name,
                    ],
                ];
            }
        }

        return [];
    }
}

if (! function_exists('canUser')) {
    function canUser($permission)
    {
        if (! Auth::check()) {
            return false;
        }

        $user = Auth::user();

        // Admin2 and Admin have all permissions
        $adminLevels = ['Admin'];
        if (in_array($user->levelStatus, $adminLevels)) {
            return true;
        }

        // Get permissions from session
        $permissions = session('user_permissions', []);

        // If session is empty, try to get from user and decode
        if (empty($permissions)) {
            $rawPermissions = $user->permissions;

            if (is_array($rawPermissions)) {
                $permissions = $rawPermissions;
            } elseif (is_string($rawPermissions) && ! empty($rawPermissions)) {
                // Try to decode JSON
                $decoded = json_decode($rawPermissions, true);
                if (is_array($decoded)) {
                    $permissions = $decoded;
                } else {
                    // Try with stripslashes
                    $decoded = json_decode(stripslashes($rawPermissions), true);
                    if (is_array($decoded)) {
                        $permissions = $decoded;
                    } else {
                        // Extract quoted strings
                        preg_match_all('/"([^"]+)"/', $rawPermissions, $matches);
                        $permissions = $matches[1] ?? [];
                    }
                }
            }

            // Store back to session
            session(['user_permissions' => $permissions]);
        }

        // Ensure permissions is array
        if (! is_array($permissions)) {
            $permissions = [];
        }

        return in_array($permission, $permissions);
    }
}

if (! function_exists('getSubmittedRequestCount')) {
    function getSubmittedRequestCount(): int
    {
        if (! Auth::check()) {
            return 0;
        }

        $accounts = getUserAccounts();
        if (empty($accounts)) {
            return 0;
        }

        $shopIds = array_column($accounts, 'id');

        $count = \App\Models\itemRequestModel::where('status', 'Submitted')
            ->where(function ($query) use ($shopIds) {
                $query->whereIn('account', $shopIds)
                    ->orWhereIn('supplierId', $shopIds);
            })
            ->distinct('requestName')
            ->count('requestName');

        return (int) $count;
    }
}

if (! function_exists('getPendingReceivingCount')) {
    function getPendingReceivingCount(): int
    {
        if (! Auth::check()) {
            return 0;
        }

        $accounts = getUserAccounts();
        if (empty($accounts)) {
            return 0;
        }

        $shopIds = array_column($accounts, 'id');

        $count = \App\Models\recevingModel::where('status', 'Not Approved')
            ->where(function ($query) use ($shopIds) {
                $query->whereIn('account', $shopIds)
                    ->orWhereIn('supplier', $shopIds);
            })
            ->count();

        return (int) $count;
    }
}

if (! function_exists('getPendingReturnCount')) {
    function getPendingReturnCount(): int
    {
        if (! Auth::check()) {
            return 0;
        }

        $accounts = getUserAccounts();
        if (empty($accounts)) {
            return 0;
        }

        $shopIds = array_column($accounts, 'id');

        $count = \App\Models\recevingModel::where('is_return', 1)
            ->where('status', 'Pending')
            ->where(function ($query) use ($shopIds) {
                $query->whereIn('account', $shopIds)
                    ->orWhereIn('supplier', $shopIds);
            })
            ->count();

        return (int) $count;
    }
}
