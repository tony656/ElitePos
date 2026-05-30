<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('item_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_id')->nullable()->after('account');
            $table->index(['shop_id']);
        });
    }

    public function down(): void
    {
        Schema::table('item_requests', function (Blueprint $table) {
            $table->dropIndex(['shop_id']);
            $table->dropColumn('shop_id');
        });
    }
};