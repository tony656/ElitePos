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
        Schema::table('accounts', function (Blueprint $table) {
            // Add 'account' column as an alias for 'name' for backward compatibility
            $table->string('account')->nullable()->after('id');
        });

        // Copy values from 'name' to 'account' for existing records
        \DB::statement('UPDATE accounts SET account = name WHERE account IS NULL');
        
        // Make 'account' column not nullable after copying data
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('account')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('account');
        });
    }
};