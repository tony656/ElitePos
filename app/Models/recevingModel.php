<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recevingModel extends Model
{
    use HasFactory;

    protected $table = 'receivings';

    protected $fillable = [
        'receivingName',
        'receivingId',
        'productId',
        'quantity',
        'price',
        'sellingPrice',
        'wholesalePrice',
        'isDebt',
        'expiry',
        'supplier',
        'account',
        'served_by',
        'status',
        'is_return',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'sellingPrice' => 'decimal:2',
        'wholesalePrice' => 'decimal:2',
        'isDebt' => 'boolean',
        'is_return' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Default status to 'Pending' if not set
    protected $attributes = [
        'status' => 'Pending'
    ];
}
