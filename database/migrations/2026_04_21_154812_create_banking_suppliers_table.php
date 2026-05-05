<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banking_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('bank_name', 255);
            $table->string('account_number', 100);
            $table->string('branch', 255)->nullable();
            $table->string('swift_code', 100)->nullable();
            $table->string('contact', 100)->nullable();
            $table->string('address', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('created_by', 255)->nullable();
            $table->string('account', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banking_suppliers');
    }
};
