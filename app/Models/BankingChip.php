<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\accountModel;
use Illuminate\Support\Facades\DB;

class BankingChip extends Model
{
    use HasFactory;

    protected $table = 'banking_chips';

    protected $fillable = [
        'shop_id',
        'transfer_id',
        'chip_amount',
        'available_chip',
        'transfer_date',
        'created_by',
        'account',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'chip_amount' => 'decimal:2',
        'available_chip' => 'decimal:2',
    ];

    /**
     * Boot method to automatically recalculate cumulative available_chip
     * for all chip entries after the current one when a chip_amount changes
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($chipEntry) {
            if ($chipEntry->isDirty('chip_amount') || $chipEntry->isDirty('available_chip')) {
                $chipEntry->recalculateCumulativeChip();
            }
        });

        static::deleted(function ($chipEntry) {
            $chipEntry->recalculateCumulativeChip();
        });
    }

    /**
     * Recalculate available_chip for all chip entries after this one
     * This maintains the cumulative running total
     */
    public function recalculateCumulativeChip()
    {
        // Get all chip entries for this shop ordered by date (and id for tie-breaking)
        $chipEntries = self::where('shop_id', $this->shop_id)
            ->orderBy('id', 'asc')
            ->get();

        $runningTotal = 0;
        foreach ($chipEntries as $entry) {
            $runningTotal += $entry->chip_amount;
            // Only update if different to avoid infinite loop
            if ($entry->available_chip != $runningTotal) {
                $entry->available_chip = $runningTotal;
                $entry->saveQuietly();
            }
        }
    }

    /**
     * Get the last chip entry for a shop (most recent)
     */
    public static function getLastChipForShop($shopId)
    {
        return self::where('shop_id', $shopId)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * Get all chip entries with available chip, ordered newest first (for LIFO deduction)
     */
    public static function getChipEntriesWithBalance($shopId)
    {
        return self::where('shop_id', $shopId)
            ->where('available_chip', '>', 0)
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->get();
    }

    /**
     * Get the shop (account) that this chip entry belongs to.
     */
    public function shop()
    {
        return $this->belongsTo(accountModel::class, 'shop_id');
    }

    /**
     * Get the transfer that created this chip entry (if any).
     */
    public function transfer()
    {
        return $this->belongsTo(BankingTransfer::class, 'transfer_id');
    }
}