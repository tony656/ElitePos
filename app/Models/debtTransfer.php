<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class debtTransfer extends Model
{
    use HasFactory;

    protected $table = 'debt_transfers';

    protected $fillable = [
        'customer',
        'shop',
        'from_invoice',
        'to_invoice',
        'transfer_date',
        'amount',
        'served_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transfer_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
