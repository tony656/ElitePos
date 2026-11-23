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
        Schema::create('debt_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('customer');        // cName of the customer
            $table->unsignedBigInteger('shop'); // account / shop id
            $table->string('from_invoice');     // source invoice (orderName) - e.g. a return
            $table->string('to_invoice');       // target invoice (orderName) - e.g. a debt
            $table->decimal('amount', 15, 2);   // transferred amount
            $table->string('served_by')->nullable(); // user who made the transfer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debt_transfers');
    }
};
