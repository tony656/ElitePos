<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->text('signature')->nullable()->after('status');
            $table->string('verified_by')->nullable()->after('signature');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->timestamp('delivery_verified_at')->nullable()->after('verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['signature', 'verified_by', 'verified_at', 'delivery_verified_at']);
        });
    }
};
