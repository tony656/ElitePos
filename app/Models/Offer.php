<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\ProductsController;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';

    protected $fillable = [
        'account',
        'product_id',
        'offer_product_id',
        'offer_quantity',
        'is_active',
        'offer_parent_products',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'offer_quantity' => 'integer',
        'offer_parent_products' => 'array',
    ];

    public function requiredItems()
    {
        return $this->hasMany(OfferItem::class, 'offer_id', 'id');
    }

    public function offeredProduct()
    {
        return $this->belongsTo(productsModel::class, 'offer_product_id', 'product_id');
    }

     protected static function booted()
     {
         static::saved(function ($offer) {
             ProductsController::clearOffersCache($offer->account);
         });
         
         static::deleted(function ($offer) {
             ProductsController::clearOffersCache($offer->account);
         });
     }
}
