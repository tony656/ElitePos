<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->text('offered_items')->nullable()->after('discount_increase');
        });

        // Add to sales table
        Schema::table('sales', function (Blueprint $table) {
            $table->text('offered_items')->nullable()->after('discount_increase');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('offered_items');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('offered_items');
        });
    }
};