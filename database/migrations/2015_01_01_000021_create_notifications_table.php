<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('notificationId')->unique();
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('info');
            $table->boolean('is_read')->default(false);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('account');
            $table->timestamps();
            
            $table->index(['account', 'created_at']);
            $table->index(['user_id']);
            $table->index(['is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};