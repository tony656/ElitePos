<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debt_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('order_name')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('account')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'account']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debt_comments');
    }
};
