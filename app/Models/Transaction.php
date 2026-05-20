<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'transaction_id',
        'shop_id',
        'transaction_type',
        'transaction_date',
        'reference_type',
        'reference_id',
        'amount',
        'chip_amount',
        'payment_method',
        'description',
        'status',
        'balance_after',
        'created_by',
        'account',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2',
        'chip_amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    /**
     * Get the shop (account) that owns the transaction.
     */
    public function shop()
    {
        return $this->belongsTo(accountModel::class, 'shop_id');
    }

    /**
     * Get the user who created this transaction.
     */
    public function creator()
    {
        return $this->belongsTo(usersModel::class, 'created_by');
    }

    /**
     * Get the parent reference model (polymorphic).
     */
    public function reference()
    {
        if (!$this->reference_type || !$this->reference_id) {
            return null;
        }

        return $this->morphTo(null, 'reference_type', 'reference_id');
    }

    /**
     * Get the balance record for this transaction's date.
     */
    public function balance()
    {
        return $this->belongsTo(TransactionBalance::class, 'balance_id');
    }

    /**
     * Get discrepancies for this transaction.
     */
    public function discrepancies()
    {
        return $this->hasMany(TransactionDiscrepancy::class, 'transaction_id');
    }

    /**
     * Scope for completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for transactions on a specific date.
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('transaction_date', $date);
    }

    /**
     * Scope for transactions in a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    /**
     * Scope for transactions by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    /**
     * Get net cash flow (positive = inflow, negative = outflow).
     */
    public function getCashFlowAttribute()
    {
        // Chip deposits are cash inflow
        // Sales, debt payments, receivings are cash inflow
        // Expenses, bank transfers, cash submissions are cash outflow
        $inflowTypes = ['sale', 'debt_payment', 'receiving', 'chip_deposit', 'cash_submission'];
        $outflowTypes = ['expense', 'bank_transfer', 'supplier_payment', 'chip_usage'];

        if (in_array($this->transaction_type, $inflowTypes)) {
            return $this->amount;
        } elseif (in_array($this->transaction_type, $outflowTypes)) {
            return -$this->amount;
        }

        return 0;
    }
}