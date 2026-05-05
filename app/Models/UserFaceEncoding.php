<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFaceEncoding extends Model
{
    use HasFactory;

    protected $table = 'user_face_encodings';

    protected $fillable = [
        'user_id',
        'face_encoding',
        'device_name',
        'browser',
        'os',
        'ip_address',
        'registered_at',
        'is_active',
    ];

    protected $casts = [
        'face_encoding' => 'array',
        'registered_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the face encoding.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}