<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('logId')->unique();
            $table->string('userName');
            $table->string('userType');
            $table->string('action');
            $table->text('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('account');
            $table->timestamps();
            
            $table->index(['account', 'created_at']);
            $table->index(['userName']);
            $table->index(['action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};