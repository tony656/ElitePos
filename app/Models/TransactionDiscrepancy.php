<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDiscrepancy extends Model
{
    use HasFactory;

    protected $table = 'transaction_discrepancies';

    protected $fillable = [
        'balance_id',
        'transaction_id',
        'shop_id',
        'discrepancy_type',
        'description',
        'expected_value',
        'actual_value',
        'impact_amount',
        'severity',
        'is_resolved',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
        'account',
    ];

    protected $casts = [
        'expected_value' => 'decimal:2',
        'actual_value' => 'decimal:2',
        'impact_amount' => 'decimal:2',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the balance record this discrepancy belongs to.
     */
    public function balance()
    {
        return $this->belongsTo(TransactionBalance::class, 'balance_id');
    }

    /**
     * Get the transaction that caused this discrepancy.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * Get the shop (account) this discrepancy belongs to.
     */
    public function shop()
    {
        return $this->belongsTo(accountModel::class, 'shop_id');
    }

    /**
     * Get the user who resolved this discrepancy.
     */
    public function resolver()
    {
        return $this->belongsTo(usersModel::class, 'resolved_by');
    }

    /**
     * Scope for unresolved discrepancies.
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    /**
     * Scope for resolved discrepancies.
     */
    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    /**
     * Scope by severity.
     */
    public function scopeOfSeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('discrepancy_type', $type);
    }

    /**
     * Mark discrepancy as resolved.
     */
    public function resolve($userId, $notes = null)
    {
        $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => $userId,
            'resolution_notes' => $notes,
        ]);
    }

    /**
     * Get severity color class.
     */
    public function getSeverityColorAttribute()
    {
        return match($this->severity) {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'amber',
            'low' => 'blue',
            default => 'gray'
        };
    }

    /**
     * Get discrepancy type label.
     */
    public function getTypeLabelAttribute()
    {
        return match($this->discrepancy_type) {
            'sales_mismatch' => 'Sales Mismatch',
            'cash_mismatch' => 'Cash Mismatch',
            'missing_transactions' => 'Missing Transactions',
            'negative_cash' => 'Negative Cash Balance',
            'chip_allocation_issue' => 'Chip Allocation Issue',
            'bank_mismatch' => 'Bank Deposit Mismatch',
            'expense_uncategorized' => 'Uncategorized Expense',
            'duplicate_transaction' => 'Duplicate Transaction',
            'orphaned_transaction' => 'Orphaned Transaction',
            'other' => 'Other Issue',
            default => ucfirst($this->discrepancy_type)
        };
    }
}