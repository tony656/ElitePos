<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receivings', function (Blueprint $table) {
            $table->id();
            $table->string('receivingId')->unique();
            $table->unsignedBigInteger('productId');
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('sellingPrice', 15, 2);
            $table->decimal('wholesalePrice', 15, 2);
            $table->boolean('isDebt')->default(false);
            $table->boolean('isPaid')->default(false);
            $table->date('expiry')->nullable();
            $table->string('supplier');
            $table->unsignedBigInteger('account');
            $table->unsignedBigInteger('served_by');
            $table->string('status')->default('Pending');
            $table->boolean('is_return')->default(false);
            $table->timestamps();
            
            $table->index(['account', 'created_at']);
            $table->index(['productId']);
            $table->index(['isPaid', 'is_return', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receivings');
    }
};