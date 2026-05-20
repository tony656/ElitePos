<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionBalance extends Model
{
    use HasFactory;

    protected $table = 'transaction_balances';

    protected $fillable = [
        'shop_id',
        'balance_date',
        'opening_balance',
        'cash_sales',
        'credit_sales',
        'cash_returns',
        'credit_returns',
        'receivables_collected',
        'payments_made',
        'expenses',
        'bank_transfers',
        'chip_deposits',
        'chip_usage',
        'cash_submitted',
        'closing_balance',
        'expected_cash',
        'cash_difference',
        'is_balanced',
        'verified_at',
        'verified_by',
        'notes',
        'account',
    ];

    protected $casts = [
        'balance_date' => 'date',
        'opening_balance' => 'decimal:2',
        'cash_sales' => 'decimal:2',
        'credit_sales' => 'decimal:2',
        'cash_returns' => 'decimal:2',
        'credit_returns' => 'decimal:2',
        'receivables_collected' => 'decimal:2',
        'payments_made' => 'decimal:2',
        'expenses' => 'decimal:2',
        'bank_transfers' => 'decimal:2',
        'chip_deposits' => 'decimal:2',
        'chip_usage' => 'decimal:2',
        'cash_submitted' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'expected_cash' => 'decimal:2',
        'cash_difference' => 'decimal:2',
        'is_balanced' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the shop (account) that owns this balance.
     */
    public function shop()
    {
        return $this->belongsTo(accountModel::class, 'shop_id');
    }

    /**
     * Get the user who verified this balance.
     */
    public function verifier()
    {
        return $this->belongsTo(usersModel::class, 'verified_by');
    }

    /**
     * Get all transactions for this balance date.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'shop_id')
            ->whereDate('transaction_date', $this->balance_date);
    }

    /**
     * Get all discrepancies for this balance.
     */
    public function discrepancies()
    {
        return $this->hasMany(TransactionDiscrepancy::class, 'balance_id');
    }

    /**
     * Get unresolved discrepancies.
     */
    public function unresolvedDiscrepancies()
    {
        return $this->discrepancies()->where('is_resolved', false);
    }

    /**
     * Check if balance is within tolerance.
     */
    public function getIsBalancedAttribute()
    {
        return abs($this->cash_difference) <= 0.01;
    }

    /**
     * Scope for balanced records.
     */
    public function scopeBalanced($query)
    {
        return $query->where('is_balanced', true);
    }

    /**
     * Scope for unbalanced records.
     */
    public function scopeUnbalanced($query)
    {
        return $query->where('is_balanced', false);
    }

    /**
     * Scope for a specific shop.
     */
    public function scopeForShop($query, $shopId)
    {
        return $query->where('shop_id', $shopId);
    }

    /**
     * Scope for date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('balance_date', [$startDate, $endDate]);
    }
}