<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountIncreaseToOrdersAndSalesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add discount_increase to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount_increase', 15, 2)->default(0)->after('discount');
        });

        // Add discount_increase to sales table
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('discount_increase', 15, 2)->default(0)->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('discount_increase');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('discount_increase');
        });
    }
}