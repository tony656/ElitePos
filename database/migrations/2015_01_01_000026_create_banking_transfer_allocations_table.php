<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banking_transfer_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_id');
            $table->unsignedBigInteger('shop_id');
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
            
            $table->index(['transfer_id']);
            $table->index(['shop_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banking_transfer_allocations');
    }
};