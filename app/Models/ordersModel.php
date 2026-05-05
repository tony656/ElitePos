<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ordersModel extends Model
{
    use HasFactory;
    protected $table = "orders";

        protected $fillable = [
        'order_id',
        'stockId',
        'orderName',
        'cName',
        'cPhone',
        'productId',
        'pQuantity',
        'productPrice',
        'totalPrice',
        'return_amount',
        'credit',
        'transactionType',
        'paid',
        'served_by',
        'coupons',
        'discount',
        'discount_increase',
        'offered_items',
        'offer_parent_product',
        'status',
        'account',
        'saleDate'
    ];
}
