<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    use HasFactory;

    protected $table = 'user_accounts';

    protected $fillable = [
        'user_id',
        'account',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(usersModel::class, 'user_id');
    }

    /**
     * Get the account relationship.
     * Renamed from account() to accountRel() to avoid conflict with the 'account' attribute.
     */
    public function accountRel()
    {
        return $this->belongsTo(accountModel::class, 'account', 'id');
    }
}