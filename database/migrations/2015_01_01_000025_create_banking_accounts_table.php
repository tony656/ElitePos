<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banking_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_number')->unique();
            $table->string('account_name');
            $table->string('bank_name');
            $table->string('branch')->nullable();
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->string('currency')->default('TSh');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('account');
            $table->timestamps();
            
            $table->index(['account']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banking_accounts');
    }
};