<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_stock_snapshots', function (Blueprint $table) {
            $table->string('category', 64)->nullable()->after('product_id');
            $table->unique(['snapshot_date', 'product_id', 'category'], 'dss_date_pid_cat_unique');
            $table->index(['category']);
        });
    }

    public function down(): void
    {
        Schema::table('daily_stock_snapshots', function (Blueprint $table) {
            $table->dropUnique('dss_date_pid_cat_unique');
            $table->dropIndex(['category']);
            $table->dropColumn('category');
        });
    }
};
