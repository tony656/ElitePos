<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expenses_id')->unique();
            $table->text('description');
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('account');
            $table->timestamps();
            
            $table->index(['account', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};