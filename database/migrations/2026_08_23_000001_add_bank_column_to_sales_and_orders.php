<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('bank', 15, 2)->default(0)->after('credit');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('bank', 15, 2)->default(0)->after('credit');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('bank');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('bank');
        });
    }
};
