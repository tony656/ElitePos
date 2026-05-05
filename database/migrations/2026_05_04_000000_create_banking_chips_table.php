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
        Schema::create('banking_chips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('transfer_id')->nullable()->constrained('banking_transfers')->onDelete('set null');
            $table->decimal('chip_amount', 15, 2)->comment('Initial chip amount for this entry');
            $table->decimal('available_chip', 15, 2)->comment('Cumulative available chip after this entry');
            $table->date('transfer_date');
            $table->string('created_by')->nullable();
            $table->string('account')->nullable();
            $table->timestamps();
            
            $table->index(['shop_id', 'transfer_date']);
            $table->index(['shop_id', 'available_chip']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banking_chips');
    }
};