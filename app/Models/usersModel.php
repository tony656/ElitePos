<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class usersModel extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'contact',
        'age',
        'levelStatus',
        'permissions',
        'account',
        'photo',
        'status',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Get all accounts this user has access to.
     */
    public function accounts()
    {
        return $this->hasMany(UserAccount::class, 'user_id');
    }

    /**
     * Get all account names this user has access to.
     */
    public function getAccountNamesAttribute()
    {
        return $this->accounts()->pluck('account');
    }

    /**
     * Check if user has access to a specific account.
     */
    public function hasAccount($account)
    {
        return $this->accounts()->where('account', $account)->exists();
    }

    /**
     * Get the primary account (where is_primary is true).
     */
    public function getPrimaryAccount()
    {
        $primary = $this->accounts()->where('is_primary', true)->first();
        return $primary ? $primary->account : $this->account;
    }
}
