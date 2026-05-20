<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customerId')->unique();
            $table->string('cName');
            $table->string('cPhone')->unique();
            $table->decimal('debt', 15, 2)->default(0);
            $table->text('address')->nullable();
            $table->unsignedBigInteger('account');
            $table->timestamps();
            
            $table->index(['account']);
            $table->index(['cPhone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};