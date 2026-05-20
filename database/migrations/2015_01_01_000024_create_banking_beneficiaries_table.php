<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banking_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('beneficiaryId')->unique();
            $table->string('bName');
            $table->string('bPhone')->nullable();
            $table->text('address')->nullable();
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('branch_name')->nullable();
            $table->unsignedBigInteger('account');
            $table->timestamps();
            
            $table->index(['account']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banking_beneficiaries');
    }
};