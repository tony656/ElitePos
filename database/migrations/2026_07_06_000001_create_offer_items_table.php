<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offer_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->string('product_id');
            $table->integer('required_quantity');
            $table->unsignedBigInteger('account');
            $table->timestamps();

            $table->index(['offer_id']);
            $table->index(['account']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_items');
    }
};
