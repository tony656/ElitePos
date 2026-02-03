<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class itemRequestModel extends Model
{
    use HasFactory;
    protected $fillable = ['requestName', 'productId', 'quantity', 'price', 'discount', 'totalPrice', 'account', 'status'];
    protected $table = 'item_requests';
}
