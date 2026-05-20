<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplierId')->unique();
            $table->string('sName');
            $table->string('sPhone')->nullable();
            $table->text('address')->nullable();
            $table->decimal('debt', 15, 2)->default(0);
            $table->unsignedBigInteger('account');
            $table->timestamps();
            
            $table->index(['account']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};