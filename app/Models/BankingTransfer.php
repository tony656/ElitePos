<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\accountModel;
use Illuminate\Support\Facades\DB;

class BankingTransfer extends Model
{
    use HasFactory;

    protected $table = 'banking_transfers';

    protected $fillable = [
        'transfer_date',
        'supplier_id',
        'beneficiary_id',
        'supplier_account_id',
        'beneficiary_account_id',
        'amount',
        'description',
        'created_by',
        'account',
        'shop_id',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the supplier that owns the transfer.
     */
    public function supplier()
    {
        return $this->belongsTo(BankingSupplier::class, 'supplier_id');
    }

    /**
     * Get the beneficiary that owns the transfer.
     */
    public function beneficiary()
    {
        return $this->belongsTo(BankingBeneficiary::class, 'beneficiary_id');
    }

    /**
     * Get the allocations for this transfer.
     */
    public function allocations()
    {
        return $this->hasMany(BankingTransferAllocation::class, 'transfer_id');
    }

    /**
     * Get the shop (account) that this transfer is allocated to.
     */
    public function shop()
    {
        return $this->belongsTo(accountModel::class, 'shop_id');
    }

    /**
     * Get the specific supplier bank account used for this transfer.
     */
    public function supplierAccount()
    {
        return $this->belongsTo(BankingAccount::class, 'supplier_account_id');
    }

    /**
     * Get the specific beneficiary bank account used for this transfer.
     */
    public function beneficiaryAccount()
    {
        return $this->belongsTo(BankingAccount::class, 'beneficiary_account_id');
    }
}