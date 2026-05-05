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
        Schema::dropIfExists('banking_accounts');
        
        Schema::create('banking_accounts', function (Blueprint $table) {
            $table->id();
            $table->morphs('accountable'); // Creates accountable_id, accountable_type and their index
            $table->string('bank_name', 255);
            $table->string('account_number', 100);
            $table->string('branch', 255)->nullable();
            $table->string('swift_code', 100)->nullable();
            $table->string('contact', 100)->nullable();
            $table->string('address', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('created_by', 255)->nullable();
            $table->string('account', 255)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            // Index for account_number (most commonly searched field)
            $table->index('account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banking_accounts');
    }
};