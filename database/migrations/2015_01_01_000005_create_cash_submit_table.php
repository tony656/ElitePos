<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cashSubmit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account');
            $table->date('report_date');
            $table->decimal('submitted_cash', 15, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['account', 'report_date']);
            $table->index(['account', 'report_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashSubmit');
    }
};