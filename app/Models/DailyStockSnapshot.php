<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStockSnapshot extends Model
{
    use HasFactory;

    protected $table = 'daily_stock_snapshots';

    protected $fillable = [
        'snapshot_date',
        'product_id',
        'opening_stock',
        'closing_stock',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'opening_stock' => 'integer',
        'closing_stock' => 'integer',
    ];
}
