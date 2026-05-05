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
        Schema::create('banking_transfers', function (Blueprint $table) {
            $table->id();
            $table->date('transfer_date');
            $table->foreignId('supplier_id')->constrained('banking_suppliers')->onDelete('cascade');
            $table->foreignId('beneficiary_id')->constrained('banking_beneficiaries')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->string('created_by')->nullable();
            $table->string('account')->nullable();
            $table->timestamps();
            
            $table->index(['transfer_date', 'account']);
            $table->index(['supplier_id', 'beneficiary_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banking_transfers');
    }
};