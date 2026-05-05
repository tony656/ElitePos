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
            $table->dropForeign(['shop_id']);
            $table->dropColumn(['shop_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banking_transfers', function (Blueprint $table) {
            $table->decimal('shop_amount', 15, 2)->nullable()->after('shop_id');
            $table->foreign('shop_id')->references('id')->on('accounts')->onDelete('SET NULL');
        });
    }
};