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
        // Add return_amount column to orders table (return is reserved keyword)
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('return_amount', 15, 2)->default(0)->after('totalPrice');
        });

        // Add return_amount column to sales table
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('return_amount', 15, 2)->default(0)->after('totalPrice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('return_amount');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('return_amount');
        });
    }
};