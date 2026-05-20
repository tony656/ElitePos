<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->unique();
            $table->string('productName');
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('totalPrice', 15, 2)->default(0);
            $table->string('payment_type')->default('cash');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('account');
            $table->timestamps();
            
            $table->index(['account', 'created_at']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_requests');
    }
};