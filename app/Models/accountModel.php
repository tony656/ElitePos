<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accountModel extends Model
{
    use HasFactory;

    protected $table = "accounts";

    protected $fillable = [
        'name',
        'location',
        'products',
        'is_primary',
    ];

    /**
     * Get the account name attribute.
     * This provides backward compatibility for code using 'account' property.
     */
    public function getAccountAttribute()
    {
        return $this->name;
    }

    /**
     * Get all accounts as a collection with 'account' as the identifier.
     */
    public function getAccountsAttribute()
    {
        return $this->name;
    }
}
