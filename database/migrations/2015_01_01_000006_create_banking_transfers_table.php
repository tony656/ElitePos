<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banking_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_id')->unique();
            $table->unsignedBigInteger('from_account');
            $table->unsignedBigInteger('to_account');
            $table->decimal('amount', 15, 2);
            $table->string('transfer_type');
            $table->string('reference_number')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->index(['from_account', 'created_at']);
            $table->index(['to_account']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banking_transfers');
    }
};