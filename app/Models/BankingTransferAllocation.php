<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankingTransferAllocation extends Model
{
    use HasFactory;

    protected $table = 'banking_transfer_allocations';

    protected $fillable = [
        'transfer_id',
        'account_id',
        'amount',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the transfer that owns the allocation.
     */
    public function transfer()
    {
        return $this->belongsTo(BankingTransfer::class, 'transfer_id');
    }

    /**
     * Get the account (shop) that receives this allocation.
     */
    public function account()
    {
        return $this->belongsTo(accountModel::class, 'account_id');
    }
}