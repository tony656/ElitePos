<?php

use App\Models\accountModel;

if (!function_exists('formatNumber')) {
    function formatNumber($num) {
        if ($num >= 1000000) {
            return number_format($num / 1000000, 1) . 'm';
        } elseif ($num >= 1000) {
            return number_format($num / 1000, 1) . 'k';
        }
        return $num;
    }
}

if (!function_exists('getSessionAccountId')) {
    /**
     * Get the account ID from the session
     * Returns the numeric account ID stored in session
     * Returns null if not set
     */
    function getSessionAccountId()
    {
        return session('account_id');
    }
}

if (!function_exists('getSessionAccountName')) {
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

if (!function_exists('getSessionAccountDisplayName')) {
    /**
     * Get the account display name from the session
     * Returns the account name or empty string if not set
     */
    function getSessionAccountDisplayName(): string
    {
        return session('account') ?? '';
    }
}
