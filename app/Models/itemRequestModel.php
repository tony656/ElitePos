<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class itemRequestModel extends Model
{
    use HasFactory;
    protected $fillable = ['requestName', 'productId', 'quantity', 'price', 'totalPrice', 'discount', 'account', 'shop_id', 'status', 'payment_type', 'assigned_to', 'assigned_by','updated_at', 'created_at'];
    protected $table = 'item_requests';
}
