<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class systemModel extends Model
{
    use HasFactory;
    protected $table = "system";
    protected $fillable = [
        'system_name',
        'system_email',
        'system_phone',
        'system_address',
        'currency',
        'currency_symbol',
        'timezone',
        'date_format',
        'time_format',
        'maintenance_mode',
        'security_flags',
        'system_mode',
        'block_signins',
        'system_shutdown',
        'bName',
        'address',
        'payment_services',
        'bank_accounts',
        'business_profile_picture',
    ];

}
