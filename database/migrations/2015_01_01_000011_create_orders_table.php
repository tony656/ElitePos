<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('orderId')->unique();
            $table->string('customerName');
            $table->string('customerPhone');
            $table->unsignedBigInteger('productId');
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('totalPrice', 15, 2);
            $table->decimal('return_amount', 15, 2)->default(0);
            $table->unsignedBigInteger('served_by');
            $table->string('transactionType');
            $table->decimal('paid', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('status')->default('completed');
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('discount_increase', 15, 2)->default(0);
            $table->decimal('offered_items', 15, 2)->default(0);
            $table->unsignedBigInteger('offer_parent_product')->nullable();
            $table->unsignedBigInteger('account');
            $table->timestamps();
            
            $table->index(['account', 'created_at']);
            $table->index(['productId']);
            $table->index(['customerPhone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};