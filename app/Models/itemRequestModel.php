<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class itemRequestModel extends Model
{
    use HasFactory;
    protected $fillable = ['requestName', 'productId', 'quantity', 'price', 'totalPrice', 'discount', 'account', 'status', 'payment_type', 'assigned_to', 'assigned_by'];
    protected $table = 'item_requests';
}
