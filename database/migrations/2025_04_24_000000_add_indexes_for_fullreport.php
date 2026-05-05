<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Indexes for sales table (heavily used in fullReport)
        Schema::table('sales', function (Blueprint $table) {
            // Composite index for the main query filter
            if (!Schema::hasColumn('sales', 'account_created_at_idx')) {
                $table->index(['account', 'created_at'], 'sales_account_created_at_idx');
            }
            // Index for date filtering
            if (!Schema::hasColumn('sales', 'created_at_idx')) {
                $table->index('created_at', 'sales_created_at_idx');
            }
            // Index for sales_id grouping
            if (!Schema::hasColumn('sales', 'sales_id_idx')) {
                $table->index('sales_id', 'sales_sales_id_idx');
            }
            // Composite index for salesName filter
            if (!Schema::hasColumn('sales', 'salesName_idx')) {
                $table->index('salesName', 'sales_salesName_idx');
            }
            // Index for served_by aggregations
            if (!Schema::hasColumn('sales', 'served_by_idx')) {
                $table->index('served_by', 'sales_served_by_idx');
            }
            // Composite index for status + transactionType
            if (!Schema::hasColumn('sales', 'status_transaction_idx')) {
                $table->index(['status', 'transactionType'], 'sales_status_transaction_idx');
            }
        });

        // Indexes for expenses table
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'account_created_at_idx')) {
                $table->index(['account', 'created_at'], 'expenses_account_created_at_idx');
            }
        });

        // Indexes for debts table (paid invoices)
        Schema::table('debts', function (Blueprint $table) {
            if (!Schema::hasColumn('debts', 'account_created_at_idx')) {
                $table->index(['account', 'created_at'], 'debts_account_created_at_idx');
            }
        });

        // Indexes for recevings table (receivings)
        Schema::table('recevings', function (Blueprint $table) {
            if (!Schema::hasColumn('recevings', 'account_created_at_idx')) {
                $table->index(['account', 'created_at'], 'recevings_account_created_at_idx');
            }
            // Composite index for isPaid + is_return + status filter
            if (!Schema::hasColumn('recevings', 'isPaid_is_return_idx')) {
                $table->index(['isPaid', 'is_return', 'status'], 'recevings_isPaid_is_return_idx');
            }
        });

        // Indexes for madeni table (supplier payments)
        Schema::table('madeni', function (Blueprint $table) {
            if (!Schema::hasColumn('madeni', 'account_created_at_idx')) {
                $table->index(['account', 'created_at'], 'madeni_account_created_at_idx');
            }
        });

        // Indexes for cash_submit table
        Schema::table('cash_submit', function (Blueprint $table) {
            if (!Schema::hasColumn('cash_submit', 'account_report_date_idx')) {
                $table->index(['account', 'report_date'], 'cash_submit_account_report_date_idx');
            }
        });

        // Index for products table (used in join for offered_items)
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'product_id_idx')) {
                $table->index('product_id', 'products_product_id_idx');
            }
            if (!Schema::hasColumn('products', 'account_idx')) {
                $table->index('account', 'products_account_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('sales_account_created_at_idx');
            $table->dropIndex('sales_created_at_idx');
            $table->dropIndex('sales_sales_id_idx');
            $table->dropIndex('sales_salesName_idx');
            $table->dropIndex('sales_served_by_idx');
            $table->dropIndex('sales_status_transaction_idx');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex('expenses_account_created_at_idx');
        });

        Schema::table('debts', function (Blueprint $table) {
            $table->dropIndex('debts_account_created_at_idx');
        });

        Schema::table('recevings', function (Blueprint $table) {
            $table->dropIndex('recevings_account_created_at_idx');
            $table->dropIndex('recevings_isPaid_is_return_idx');
        });

        Schema::table('madeni', function (Blueprint $table) {
            $table->dropIndex('madeni_account_created_at_idx');
        });

        Schema::table('cash_submit', function (Blueprint $table) {
            $table->dropIndex('cash_submit_account_report_date_idx');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_product_id_idx');
            $table->dropIndex('products_account_idx');
        });
    }
};