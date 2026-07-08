<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salsModel extends Model
{
    use HasFactory;

    protected $table = 'sales';
        protected $fillable = [
            'sales_id',
            'salesName',
            'stockId',
            'cName',
            'cPhone',
            'productId',
            'pQuantity',
            'productPrice',
            'totalPrice',
            'return_amount',
            'served_by',
            'credit',
            'transactionType',
            'paid',
            'coupons',
            'status',
            'discount',
            'discount_increase',
            'offered_items',
            'offer_parent_product',
            'offer_parent_products',
            'account',
            'status',
            'created_at'
        ];
}
