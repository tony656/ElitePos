<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class madeni extends Model
{
    use HasFactory;

    protected $table = 'madeni';

     protected $fillable = [
        'supplier_id',
        'receivings_id',
        'amount_paid',
        'payment_date',
        'payment_method',
        'reference_number',
        'notes'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship to receiving order
     */
    public function receiving()
    {
        return $this->belongsTo(recevingModel::class, 'receivingId');
    }

    /**
     * Get total amount paid for a specific receiving order
     */
    public static function getTotalPaidForOrder($receivingId)
    {
        return self::where('receivings_id', $receivingId)->sum('amount');
    }

    /**
     * Check if order is fully paid
     */
    public static function isOrderFullyPaid($receivingId, $totalDue)
    {
        $totalPaid = self::getTotalPaidForOrder($receivingId);
        return $totalPaid >= $totalDue;
    }
}
