<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_stock_snapshots', function (Blueprint $table) {
            $table->id();
            $table->date('snapshot_date');
            $table->string('product_id');
            $table->integer('opening_stock')->default(0);
            $table->integer('closing_stock')->default(0);
            $table->timestamps();

            $table->unique(['snapshot_date', 'product_id']);
            $table->index(['snapshot_date']);
            $table->index(['product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_stock_snapshots');
    }
};
