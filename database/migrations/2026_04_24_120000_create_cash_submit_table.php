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
        Schema::create('cash_submit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account');
            $table->decimal('submitted_cash', 15, 2)->default(0);
            $table->dateTime('report_date');
            $table->unsignedBigInteger('parent_account')->nullable()->comment('For multi-account tracking');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('account')->references('id')->on('account')->onDelete('cascade');
            $table->foreign('parent_account')->references('id')->on('account')->onDelete('set null');
        });

        // Add index for better query performance
        Schema::table('cash_submit', function (Blueprint $table) {
            $table->index(['account', 'report_date'], 'idx_cash_submit_account_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_submit');
    }
};