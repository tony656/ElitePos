<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salsModel extends Model
{
    use HasFactory;

    protected $table = 'sales';
        protected $fillable = [
        'sales_id', // Add this line
        'salesName',
        'stockId',
        'cName',
        'cPhone',
        'productId',
        'pQuantity',
        'productPrice',
        'totalPrice',
        'served_by',
        'credit',
        'transactionType',
        'paid',
        'coupons',
        'status',
        'discount',
        'served_by',
        'account'      
    ];
}
