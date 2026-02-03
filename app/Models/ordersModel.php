<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ordersModel extends Model
{
    use HasFactory;
    protected $table = "orders";

        protected $fillable = [
        'order_id', // Add this line
        'stockId',
        'orderName',
        'cName',
        'cPhone',
        'productId',
        'pQuantity',
        'productPrice',
        'totalPrice',
        'credit',
        'transactionType',
        'paid',
        'served_by',
        'coupons',
        'discount',
        'served_by',
        'status',
        'account'
        
    ];
}
