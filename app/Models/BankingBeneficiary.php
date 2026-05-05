<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankingBeneficiary extends Model
{
    use HasFactory;

    protected $table = 'banking_beneficiaries';

    protected $fillable = [
        'name',
        'bank_name',
        'account_number',
        'branch',
        'swift_code',
        'contact',
        'address',
        'description',
        'created_by',
        'account',
    ];

    /**
     * Get the bank accounts for this beneficiary
     */
    public function accounts()
    {
        return $this->morphMany(BankingAccount::class, 'accountable');
    }
}