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
        // Indexes for sales table - critical for fullReport queries
        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table) {
                // Add account column if it doesn't exist
                if (!Schema::hasColumn('sales', 'account')) {
                    $table->unsignedBigInteger('account')->after('id');
                }
                
                // Index for date range filtering
                if (!Schema::hasColumn('sales', 'idx_sales_account_created')) {
                    $table->index(['account', 'created_at'], 'idx_sales_account_created');
                }
                
                // Index for grouping by date
                if (!Schema::hasColumn('sales', 'idx_sales_account_date_sales')) {
                    $table->index(['account', 'created_at', 'sales_id'], 'idx_sales_account_date_sales');
                }
                
                // Index for salesName filtering
                if (!Schema::hasColumn('sales', 'idx_sales_account_salesName')) {
                    $table->index(['account', 'salesName'], 'idx_sales_account_salesName');
                }
                
                // Index for status and transactionType filtering
                if (!Schema::hasColumn('sales', 'idx_sales_account_status')) {
                    $table->index(['account', 'status', 'transactionType'], 'idx_sales_account_status');
                }
                
                // Index for productId joins
                if (!Schema::hasColumn('sales', 'idx_sales_productId')) {
                    $table->index(['productId'], 'idx_sales_productId');
                }
            });
        }

        // Indexes for expenses table
        if (Schema::hasTable('expenses')) {
            Schema::table('expenses', function (Blueprint $table) {
                if (!Schema::hasColumn('expenses', 'account')) {
                    $table->unsignedBigInteger('account')->after('id');
                }
                if (!Schema::hasColumn('expenses', 'idx_expenses_account_date')) {
                    $table->index(['account', 'created_at'], 'idx_expenses_account_date');
                }
            });
        }

        // Indexes for debts table (paid invoices)
        if (Schema::hasTable('debts')) {
            Schema::table('debts', function (Blueprint $table) {
                if (!Schema::hasColumn('debts', 'account')) {
                    $table->unsignedBigInteger('account')->after('id');
                }
                if (!Schema::hasColumn('debts', 'idx_debts_account_date')) {
                    $table->index(['account', 'created_at'], 'idx_debts_account_date');
                }
            });
        }

        // Indexes for recevings table (receivings)
        if (Schema::hasTable('receivings')) {
            Schema::table('receivings', function (Blueprint $table) {
                if (!Schema::hasColumn('receivings', 'account')) {
                    $table->unsignedBigInteger('account')->after('id');
                }
                // Composite index for the complex WHERE conditions
                if (!Schema::hasColumn('receivings', 'idx_receivings_account_paid_return_date')) {
                    $table->index(['account', 'isPaid', 'is_return', 'created_at'], 'idx_receivings_account_paid_return_date');
                }
                if (!Schema::hasColumn('receivings', 'idx_receivings_account_status')) {
                    $table->index(['account', 'status'], 'idx_receivings_account_status');
                }
                if (!Schema::hasColumn('receivings', 'idx_receivings_productId')) {
                    $table->index(['productId'], 'idx_receivings_productId');
                }
            });
        }

        // Indexes for madeni table (supplier payments)
        if (Schema::hasTable('madeni')) {
            Schema::table('madeni', function (Blueprint $table) {
                if (!Schema::hasColumn('madeni', 'account')) {
                    $table->unsignedBigInteger('account')->after('id');
                }
                if (!Schema::hasColumn('madeni', 'idx_madeni_account_date')) {
                    $table->index(['account', 'created_at'], 'idx_madeni_account_date');
                }
            });
        }

        // Indexes for cash_submit table
        if (Schema::hasTable('cash_submit')) {
            Schema::table('cash_submit', function (Blueprint $table) {
                if (!Schema::hasColumn('cash_submit', 'account')) {
                    $table->unsignedBigInteger('account')->after('id');
                }
                if (!Schema::hasColumn('cash_submit', 'idx_cash_submit_account_date')) {
                    $table->index(['account', 'report_date'], 'idx_cash_submit_account_date');
                }
            });
        }

        // Indexes for products table (for joins)
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'idx_products_account_id')) {
                    $table->index(['account', 'product_id'], 'idx_products_account_id');
                }
                if (!Schema::hasColumn('products', 'idx_products_id')) {
                    $table->index(['product_id'], 'idx_products_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('idx_sales_account_created');
            $table->dropIndex('idx_sales_account_date_sales');
            $table->dropIndex('idx_sales_account_salesName');
            $table->dropIndex('idx_sales_account_status');
            $table->dropIndex('idx_sales_productId');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex('idx_expenses_account_date');
        });

        Schema::table('debts', function (Blueprint $table) {
            $table->dropIndex('idx_debts_account_date');
        });

        Schema::table('receivings', function (Blueprint $table) {
            $table->dropIndex('idx_receivings_account_paid_return_date');
            $table->dropIndex('idx_receivings_account_status');
            $table->dropIndex('idx_receivings_productId');
        });

        Schema::table('madeni', function (Blueprint $table) {
            $table->dropIndex('idx_madeni_account_date');
        });

        Schema::table('cash_submit', function (Blueprint $table) {
            $table->dropIndex('idx_cash_submit_account_date');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_account_id');
            $table->dropIndex('idx_products_id');
        });
    }
};