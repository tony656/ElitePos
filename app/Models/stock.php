<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stock extends Model
{
    use HasFactory;
    protected $table = "stock";

        protected $fillable = [
            'account',
            'productId',
            'name',
            'sQuantity',
            'amount',
            'profit',
            'bPrice',
            'sPrice',
            'created_at',
            'profit'
        ];
}
