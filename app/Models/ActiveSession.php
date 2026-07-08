<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveSession extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'ip_address', 'user_agent',
        'device_name', 'browser', 'os', 'device_type',
        'is_authorized', 'is_blocked', 'status', 'last_activity'
    ];

    protected $casts = [
        'is_authorized' => 'boolean',
        'is_blocked' => 'boolean',
        'last_activity' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}