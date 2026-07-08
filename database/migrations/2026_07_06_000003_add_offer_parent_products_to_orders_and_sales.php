<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->json('offer_parent_products')->nullable()->after('offer_parent_product');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->json('offer_parent_products')->nullable()->after('offer_parent_product');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('offer_parent_products');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('offer_parent_products');
        });
    }
};
