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
        Schema::table('banking_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_account_id')->nullable()->after('supplier_id');
            $table->unsignedBigInteger('beneficiary_account_id')->nullable()->after('beneficiary_id');
            
            // Add foreign keys
            $table->foreign('supplier_account_id')
                  ->references('id')
                  ->on('banking_accounts')
                  ->onDelete('set null');
                  
            $table->foreign('beneficiary_account_id')
                  ->references('id')
                  ->on('banking_accounts')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banking_transfers', function (Blueprint $table) {
            $table->dropForeign(['supplier_account_id']);
            $table->dropForeign(['beneficiary_account_id']);
            $table->dropColumn(['supplier_account_id', 'beneficiary_account_id']);
        });
    }
};