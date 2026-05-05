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
        Schema::create('banking_transfer_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_id')->constrained('banking_transfers')->onDelete('cascade');
            $table->unsignedBigInteger('account_id')->comment('Shop/Account ID');
            $table->decimal('amount', 15, 2);
            $table->string('created_by')->nullable();
            $table->timestamps();

            // Foreign key to accounts table
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            
            // Ensure unique combination of transfer and account
            $table->unique(['transfer_id', 'account_id']);
            
            // Index for efficient querying
            $table->index('account_id');
            $table->index(['transfer_id', 'account_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banking_transfer_allocations');
    }
};