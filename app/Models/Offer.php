<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';

    protected $fillable = [
        'account',
        'product_id',
        'offer_product_id',
        'required_quantity',
        'offer_quantity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'required_quantity' => 'integer',
        'offer_quantity' => 'integer',
    ];

    // Get the product that has the offer
    public function product()
    {
        return $this->belongsTo(productsModel::class, 'product_id', 'product_id');
    }

    // Get the offered product
    public function offeredProduct()
    {
        return $this->belongsTo(productsModel::class, 'offer_product_id', 'product_id');
    }
}