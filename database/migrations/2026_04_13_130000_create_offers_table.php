<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('account');
            $table->string('product_id'); // The product that has the offer
            $table->string('offer_product_id'); // The product being offered
            $table->integer('required_quantity')->default(1); // Quantity needed to trigger offer
            $table->integer('offer_quantity')->default(1); // Quantity of offered item
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};