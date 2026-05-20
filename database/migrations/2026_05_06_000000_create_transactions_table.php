<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id', 50)->unique()->comment('Unique transaction identifier');
            $table->enum('transaction_type', [
                'sale', 'return', 'expense', 'debt_payment', 'debt_receiving',
                'cash_submit', 'cash_delete', 'bank_transfer', 'chip_deposit', 'chip_use',
                'invoice_payment', 'receiving_payment', 'order', 'order_return'
            ])->comment('Type of transaction');
            $table->decimal('amount', 15, 2)->comment('Transaction amount');
            $table->decimal('chip_amount', 15, 2)->default(0)->comment('Chip amount if applicable');
            $table->string('reference_type', 100)->nullable()->comment('Polymorphic model type');
            $table->unsignedBigInteger('reference_id')->nullable()->comment('Polymorphic model ID');
            $table->string('shop_id', 50)->nullable()->comment('Shop/account identifier');
            $table->date('transaction_date')->comment('Date of transaction');
            $table->time('transaction_time')->comment('Time of transaction');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable()->comment('Additional transaction data');
            $table->unsignedBigInteger('created_by')->nullable()->comment('User who created');
            $table->timestamps();

            $table->index(['transaction_date', 'shop_id']);
            $table->index(['transaction_type', 'status']);
            $table->index(['reference_type', 'reference_id']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};