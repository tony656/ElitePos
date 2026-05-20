<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add missing columns needed for optimized queries.
     */
    public function up(): void
    {
        // Ensure banking_transfers has shop_id column (used in AllShopReport)
        if (Schema::hasTable('banking_transfers')) {
            if (!Schema::hasColumn('banking_transfers', 'shop_id')) {
                Schema::table('banking_transfers', function (Blueprint $table) {
                    $table->unsignedBigInteger('shop_id')->nullable()->after('account');
                    $table->index(['shop_id'], 'idx_banking_transfers_shop_id');
                });
            }
        }

        // Ensure banking_chips has shop_id column (already exists in model)
        // Ensure banking_chips has transfer_date column (already exists in model)
        
        // Add index on sales.status for the status != 'Return' filter
        if (Schema::hasTable('sales')) {
            if (!Schema::hasColumn('sales', 'idx_sales_status')) {
                Schema::table('sales', function (Blueprint $table) {
                    $table->index(['status'], 'idx_sales_status');
                });
            }
        }

        // Add index on receivings.is_return for the status filter
        if (Schema::hasTable('receivings')) {
            if (!Schema::hasColumn('receivings', 'idx_receivings_is_return')) {
                Schema::table('receivings', function (Blueprint $table) {
                    $table->index(['is_return'], 'idx_receivings_is_return');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('banking_transfers')) {
            Schema::table('banking_transfers', function (Blueprint $table) {
                $table->dropIndex('idx_banking_transfers_shop_id');
                $table->dropColumn('shop_id');
            });
        }

        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->dropIndex('idx_sales_status');
            });
        }

        if (Schema::hasTable('receivings')) {
            Schema::table('receivings', function (Blueprint $table) {
                $table->dropIndex('idx_receivings_is_return');
            });
        }
    }
};