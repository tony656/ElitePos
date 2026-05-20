<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('adsId')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('target_url')->nullable();
            $table->string('position')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('account');
            $table->timestamps();
            
            $table->index(['account']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};