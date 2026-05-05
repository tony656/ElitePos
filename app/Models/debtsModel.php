<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class debtsModel extends Model
{
    use HasFactory;

    protected $table = 'debts';

    protected $fillable = [
        'cName',
        'cId',
        'debtId',
        'orderId',
        'amount',
        'account',
        'payment_method',
        'chip_amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'chip_amount' => 'decimal:2',
    ];
}
