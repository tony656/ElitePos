<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id')->comment('Shop/account ID');
            $table->date('balance_date')->comment('Date of balance');
            $table->decimal('expected_cash', 15, 2)->default(0)->comment('Expected cash amount');
            $table->decimal('cash_submitted', 15, 2)->default(0)->comment('Actual cash submitted');
            $table->decimal('cash_difference', 15, 2)->default(0)->comment('Difference: expected - submitted');
            $table->decimal('total_sales', 15, 2)->default(0);
            $table->decimal('cash_sales', 15, 2)->default(0);
            $table->decimal('credit_sales', 15, 2)->default(0);
            $table->decimal('total_return', 15, 2)->default(0);
            $table->decimal('expenses', 15, 2)->default(0);
            $table->decimal('cash_receivings', 15, 2)->default(0);
            $table->decimal('paid_receivings', 15, 2)->default(0);
            $table->decimal('paid_invoices', 15, 2)->default(0);
            $table->decimal('total_bank', 15, 2)->default(0);
            $table->decimal('bank_diff', 15, 2)->default(0);
            $table->decimal('total_chip', 15, 2)->default(0);
            $table->decimal('chip_used', 15, 2)->default(0);
            $table->decimal('total_profit', 15, 2)->default(0);
            $table->decimal('total_transactions', 15, 2)->default(0);
            $table->boolean('is_balanced')->default(false)->comment('Whether all transactions balance');
            $table->json('balance_issues')->nullable()->comment('Details of any balance issues');
            $table->unsignedBigInteger('calculated_by')->nullable()->comment('User who last calculated');
            $table->timestamps();

            $table->unique(['shop_id', 'balance_date']);
            $table->foreign('calculated_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['balance_date', 'is_balanced']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_balances');
    }
};