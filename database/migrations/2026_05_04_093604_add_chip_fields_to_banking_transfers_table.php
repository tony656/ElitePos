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
        Schema::table('banking_transfers', function (Blueprint $table) {
            $table->decimal('chip', 15, 2)->nullable()->after('amount');
            $table->decimal('availableChip', 15, 2)->nullable()->after('chip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banking_transfers', function (Blueprint $table) {
            $table->dropColumn(['chip', 'availableChip']);
        });
    }
};
