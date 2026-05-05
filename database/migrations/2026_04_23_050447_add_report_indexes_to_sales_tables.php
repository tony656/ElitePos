<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Indexes for sales table - critical for report queries
        // Using prefix indexes for VARCHAR/TEXT columns to stay under 1000-byte limit with utf8mb4
        
        // Check and create indexes only if they don't exist
        $this->createIndexIfNotExists('sales', 'idx_sales_account_created', 'INDEX', ['account(50)', 'created_at']);
        $this->createIndexIfNotExists('sales', 'idx_sales_product_account', 'INDEX', ['productId(50)', 'account(50)']);
        $this->createIndexIfNotExists('sales', 'idx_sales_transaction_type', 'INDEX', ['transactionType(50)']);
        $this->createIndexIfNotExists('sales', 'idx_sales_status', 'INDEX', ['status(50)']);
        $this->createIndexIfNotExists('sales', 'idx_sales_offered_date', 'INDEX', ['offered_items(10)', 'account(50)', 'created_at']);
        $this->createIndexIfNotExists('sales', 'idx_sales_return_amount', 'INDEX', ['return_amount']);
        $this->createIndexIfNotExists('sales', 'idx_sales_credit', 'INDEX', ['credit']);
        $this->createIndexIfNotExists('sales', 'idx_sales_paid', 'INDEX', ['paid']);

        // Indexes for receivings table
        $this->createIndexIfNotExists('receivings', 'idx_receivings_account_created', 'INDEX', ['account(50)', 'created_at']);
        $this->createIndexIfNotExists('receivings', 'idx_receivings_paid_return', 'INDEX', ['isPaid', 'is_return']);
        $this->createIndexIfNotExists('receivings', 'idx_receivings_status', 'INDEX', ['status(50)']);
        $this->createIndexIfNotExists('receivings', 'idx_receivings_full_query', 'INDEX', ['account(50)', 'isPaid', 'is_return', 'created_at']);

        // Indexes for expenses table
        $this->createIndexIfNotExists('expenses', 'idx_expenses_account_created', 'INDEX', ['account(50)', 'created_at']);
        $this->createIndexIfNotExists('expenses', 'idx_expenses_amount', 'INDEX', ['amount']);

        // Indexes for debts table (paid invoices)
        $this->createIndexIfNotExists('debts', 'idx_debts_account_created', 'INDEX', ['account(50)', 'created_at']);
        $this->createIndexIfNotExists('debts', 'idx_debts_amount', 'INDEX', ['amount']);

        // Indexes for cash_submit table
        $this->createIndexIfNotExists('cash_submit', 'idx_cashsubmit_account_date', 'INDEX', ['account(50)', 'report_date']);

        // Indexes for madeni table (supplier payments)
        $this->createIndexIfNotExists('madeni', 'idx_madeni_account_created', 'INDEX', ['account(50)', 'created_at']);
        $this->createIndexIfNotExists('madeni', 'idx_madeni_amount', 'INDEX', ['amount']);

        // Indexes for products table (for joins)
        $this->createIndexIfNotExists('products', 'idx_products_account_product', 'INDEX', ['account(50)', 'product_id(50)']);
        $this->createIndexIfNotExists('products', 'idx_products_bprice', 'INDEX', ['bPrice']);
        $this->createIndexIfNotExists('products', 'idx_products_sprice', 'INDEX', ['sPrice']);

        // Indexes for account table (using Schema for simple indexes)
        Schema::table('account', function (Blueprint $table) {
            if (!Schema::hasColumn('account', 'id')) return;
            try {
                $table->index('id', 'idx_account_id');
            } catch (\Exception $e) {
                // Index may already exist
            }
            try {
                $table->index('name', 'idx_account_name');
            } catch (\Exception $e) {
                // Index may already exist
            }
        });
    }

    /**
     * Helper method to create index only if it doesn't exist
     */
    private function createIndexIfNotExists($table, $indexName, $type, $columns)
    {
        // Check if index exists
        $exists = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.statistics 
            WHERE table_schema = DATABASE() 
            AND table_name = ? 
            AND index_name = ?
        ", [$table, $indexName]);

        if (empty($exists) || $exists[0]->count == 0) {
            $columnsStr = implode(', ', $columns);
            DB::statement("CREATE INDEX {$indexName} ON {$table} ({$columnsStr})");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('idx_sales_account_created');
            $table->dropIndex('idx_sales_product_account');
            $table->dropIndex('idx_sales_transaction_type');
            $table->dropIndex('idx_sales_status');
            $table->dropIndex('idx_sales_offered_date');
            $table->dropIndex('idx_sales_return_amount');
            $table->dropIndex('idx_sales_credit');
            $table->dropIndex('idx_sales_paid');
        });

        Schema::table('receivings', function (Blueprint $table) {
            $table->dropIndex('idx_receivings_account_created');
            $table->dropIndex('idx_receivings_paid_return');
            $table->dropIndex('idx_receivings_status');
            $table->dropIndex('idx_receivings_full_query');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex('idx_expenses_account_created');
            $table->dropIndex('idx_expenses_amount');
        });

        Schema::table('debts', function (Blueprint $table) {
            $table->dropIndex('idx_debts_account_created');
            $table->dropIndex('idx_debts_amount');
        });

        Schema::table('cash_submit', function (Blueprint $table) {
            $table->dropIndex('idx_cashsubmit_account_date');
        });

        Schema::table('madeni', function (Blueprint $table) {
            $table->dropIndex('idx_madeni_account_created');
            $table->dropIndex('idx_madeni_amount');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_account_product');
            $table->dropIndex('idx_products_bprice');
            $table->dropIndex('idx_products_sprice');
        });

        Schema::table('account', function (Blueprint $table) {
            $table->dropIndex('idx_account_id');
            $table->dropIndex('idx_account_name');
        });
    }
};
