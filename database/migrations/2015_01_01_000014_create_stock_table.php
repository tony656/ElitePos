<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->string('stockName');
            $table->string('stockId')->unique();
            $table->string('category')->nullable();
            $table->decimal('cost_price', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->integer('quantity')->default(0);
            $table->integer('minimum_quantity')->default(0);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('account');
            $table->timestamps();
            
            $table->index(['account']);
            $table->index(['stockId']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};