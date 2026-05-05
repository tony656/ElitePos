<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankingAccount extends Model
{
    use HasFactory;

    protected $table = 'banking_accounts';

    protected $fillable = [
        'accountable_id',
        'accountable_type',
        'bank_name',
        'account_number',
        'branch',
        'swift_code',
        'contact',
        'address',
        'description',
        'created_by',
        'account',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get the parent accountable model (supplier or beneficiary)
     */
    public function accountable()
    {
        return $this->morphTo();
    }
}