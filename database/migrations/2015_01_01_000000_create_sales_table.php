<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sales_id')->unique();
            $table->string('salesName');
            $table->unsignedBigInteger('stockId');
            $table->string('cName');
            $table->string('cPhone');
            $table->unsignedBigInteger('productId');
            $table->integer('pQuantity');
            $table->decimal('productPrice', 15, 2);
            $table->decimal('totalPrice', 15, 2);
            $table->decimal('return_amount', 15, 2)->default(0);
            $table->unsignedBigInteger('served_by');
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('transactionType', 50);
            $table->decimal('paid', 15, 2)->default(0);
            $table->decimal('coupons', 15, 2)->default(0);
            $table->string('status', 50)->default('completed');
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('discount_increase', 15, 2)->default(0);
            $table->decimal('offered_items', 15, 2)->default(0);
            $table->unsignedBigInteger('offer_parent_product')->nullable();
            $table->unsignedBigInteger('account');
            $table->timestamps();
            
            $table->index(['account', 'created_at']);
            $table->index(['sales_id']);
            $table->index(['productId']);
            $table->index(['status', 'transactionType']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};