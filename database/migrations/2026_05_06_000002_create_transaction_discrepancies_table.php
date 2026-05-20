<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_discrepancies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('balance_id')->comment('Reference to transaction_balance');
            $table->string('discrepancy_type', 100)->comment('Type of discrepancy');
            $table->string('type_label', 150)->nullable()->comment('Human readable label');
            $table->text('description')->comment('Detailed description of the issue');
            $table->decimal('expected_value', 15, 2)->comment('Expected value');
            $table->decimal('actual_value', 15, 2)->comment('Actual value');
            $table->decimal('impact_amount', 15, 2)->comment('Financial impact');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->string('transaction_id', 50)->nullable()->comment('Related transaction if any');
            $table->string('reference_type', 100)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->text('resolution_notes')->nullable();
            $table->unsignedBigInteger('resolved_by')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('balance_id')->references('id')->on('transaction_balances')->onDelete('cascade');
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['balance_id', 'is_resolved', 'severity']);
            $table->index(['discrepancy_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_discrepancies');
    }
};