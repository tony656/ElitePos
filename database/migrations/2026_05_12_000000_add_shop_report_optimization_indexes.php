<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add only the most critical missing indexes for shop report optimization.
     * Skip indexes that may cause key length issues.
     */
    public function up(): void
    {
        // 1. banking_transfers: composite index for shop_id + transfer_date (CRITICAL)
        if (Schema::hasTable('banking_transfers')) {
            $result = DB::select("SHOW INDEX FROM banking_transfers WHERE Key_name = 'idx_banking_transfers_shop_date'");
            if (empty($result)) {
                try {
                    DB::statement('ALTER TABLE banking_transfers ADD INDEX idx_banking_transfers_shop_date (shop_id, transfer_date)');
                } catch (\Exception $e) {
                    \Log::warning('Failed to create idx_banking_transfers_shop_date: ' . $e->getMessage());
                }
            }
        }

        // 2. banking_chips: composite index for shop_id + transfer_date (CRITICAL)
        if (Schema::hasTable('banking_chips')) {
            $result = DB::select("SHOW INDEX FROM banking_chips WHERE Key_name = 'idx_banking_chips_shop_date'");
            if (empty($result)) {
                try {
                    DB::statement('ALTER TABLE banking_chips ADD INDEX idx_banking_chips_shop_date (shop_id, transfer_date)');
                } catch (\Exception $e) {
                    \Log::warning('Failed to create idx_banking_chips_shop_date: ' . $e->getMessage());
                }
            }
        }

        // 3. cash_submit: index on report_date (HELPFUL)
        if (Schema::hasTable('cash_submit')) {
            $result = DB::select("SHOW INDEX FROM cash_submit WHERE Key_name = 'idx_cash_submit_date'");
            if (empty($result)) {
                try {
                    DB::statement('ALTER TABLE cash_submit ADD INDEX idx_cash_submit_date (report_date)');
                } catch (\Exception $e) {
                    \Log::warning('Failed to create idx_cash_submit_date: ' . $e->getMessage());
                }
            }
        }

        // 4. sales: index on salesName for filtering (HELPFUL)
        if (Schema::hasTable('sales')) {
            $result = DB::select("SHOW INDEX FROM sales WHERE Key_name = 'idx_sales_account_salesName'");
            if (empty($result)) {
                try {
                    // Use prefix to avoid key length issues
                    DB::statement('ALTER TABLE sales ADD INDEX idx_sales_account_salesName (account, salesName(50))');
                } catch (\Exception $e) {
                    \Log::warning('Failed to create idx_sales_account_salesName: ' . $e->getMessage());
                }
            }
        }

        // 5. receivings: composite index for account + isPaid + created_at (CRITICAL)
        if (Schema::hasTable('receivings')) {
            $result = DB::select("SHOW INDEX FROM receivings WHERE Key_name = 'idx_receivings_account_paid_date'");
            if (empty($result)) {
                try {
                    DB::statement('ALTER TABLE receivings ADD INDEX idx_receivings_account_paid_date (account, isPaid, created_at)');
                } catch (\Exception $e) {
                    \Log::warning('Failed to create idx_receivings_account_paid_date: ' . $e->getMessage());
                }
            }
        }

        // 6. products: composite index for account + product_id (HELPFUL for joins)
        if (Schema::hasTable('products')) {
            $result = DB::select("SHOW INDEX FROM products WHERE Key_name = 'idx_products_account_id'");
            if (empty($result)) {
                try {
                    DB::statement('ALTER TABLE products ADD INDEX idx_products_account_id (account, product_id)');
                } catch (\Exception $e) {
                    \Log::warning('Failed to create idx_products_account_id: ' . $e->getMessage());
                }
            }
        }

        // 7. transaction_balances: composite index for shop_id + balance_date (CRITICAL)
        if (Schema::hasTable('transaction_balances')) {
            $result = DB::select("SHOW INDEX FROM transaction_balances WHERE Key_name = 'idx_balances_shop_date'");
            if (empty($result)) {
                try {
                    DB::statement('ALTER TABLE transaction_balances ADD INDEX idx_balances_shop_date (shop_id, balance_date)');
                } catch (\Exception $e) {
                    \Log::warning('Failed to create idx_balances_shop_date: ' . $e->getMessage());
                }
            }
        }

        // 8. transaction_discrepancies: composite index for balance_id + is_resolved (HELPFUL)
        if (Schema::hasTable('transaction_discrepancies')) {
            $result = DB::select("SHOW INDEX FROM transaction_discrepancies WHERE Key_name = 'idx_discrepancies_balance_resolved'");
            if (empty($result)) {
                try {
                    DB::statement('ALTER TABLE transaction_discrepancies ADD INDEX idx_discrepancies_balance_resolved (balance_id, is_resolved)');
                } catch (\Exception $e) {
                    \Log::warning('Failed to create idx_discrepancies_balance_resolved: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $indexes = [
            'transaction_discrepancies' => ['idx_discrepancies_balance_resolved'],
            'transaction_balances' => ['idx_balances_shop_date'],
            'products' => ['idx_products_account_id'],
            'receivings' => ['idx_receivings_account_paid_date'],
            'sales' => ['idx_sales_account_salesName'],
            'cash_submit' => ['idx_cash_submit_date'],
            'banking_chips' => ['idx_banking_chips_shop_date'],
            'banking_transfers' => ['idx_banking_transfers_shop_date'],
        ];

        foreach ($indexes as $table => $indexList) {
            if (Schema::hasTable($table)) {
                foreach ($indexList as $indexName) {
                    $result = DB::select("SHOW INDEX FROM `$table` WHERE Key_name = ?", [$indexName]);
                    if (!empty($result)) {
                        try {
                            DB::statement("ALTER TABLE `$table` DROP INDEX `$indexName`");
                        } catch (\Exception $e) {
                            \Log::warning("Failed to drop index $indexName on $table: " . $e->getMessage());
                        }
                    }
                }
            }
        }
    }
};