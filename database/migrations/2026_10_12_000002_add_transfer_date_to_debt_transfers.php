<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('debt_transfers', function (Blueprint $table) {
            if (!Schema::hasColumn('debt_transfers', 'transfer_date')) {
                $table->date('transfer_date')->nullable()->after('to_invoice');
            }
        });
    }

    public function down(): void
    {
        Schema::table('debt_transfers', function (Blueprint $table) {
            if (Schema::hasColumn('debt_transfers', 'transfer_date')) {
                $table->dropColumn('transfer_date');
            }
        });
    }
};
