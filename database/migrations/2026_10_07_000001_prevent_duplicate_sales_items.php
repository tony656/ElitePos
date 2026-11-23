<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('sales')) {
            // Add unique index to prevent future duplicates
            // This prevents same product being added twice to the same sale (for regular items and offered items separately)
            try {
                Schema::table('sales', function (Blueprint $table) {
                    $table->unique(['account', 'sales_id', 'productId', 'offered_items'], 'uniq_sale_product_offered');
                });
            } catch (\Exception $e) {
                // Index might already exist or there are duplicates
                \Log::warning('Could not add unique index: ' . $e->getMessage());
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('sales')) {
            try {
                Schema::table('sales', function (Blueprint $table) {
                    $table->dropUnique('uniq_sale_product_offered');
                });
            } catch (\Exception $e) {
                // Index might not exist
            }
        }
    }
};