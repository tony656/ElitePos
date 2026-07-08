<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferItem extends Model
{
    use HasFactory;

    protected $table = 'offer_items';

    protected $fillable = [
        'offer_id',
        'product_id',
        'required_quantity',
        'account',
    ];

    protected $casts = [
        'required_quantity' => 'integer',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(productsModel::class, 'product_id', 'product_id');
    }
}
