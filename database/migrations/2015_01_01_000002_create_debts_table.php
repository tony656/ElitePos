<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->string('debtId')->unique();
            $table->string('cName');
            $table->unsignedBigInteger('cId');
            $table->unsignedBigInteger('orderId');
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('account');
            $table->string('payment_method')->default('cash');
            $table->decimal('chip_amount', 15, 2)->default(0);
            $table->timestamps();
            
            $table->index(['account', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};