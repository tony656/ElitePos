<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system', function (Blueprint $table) {
            $table->id();
            $table->string('system_name');
            $table->string('system_email')->nullable();
            $table->string('system_phone')->nullable();
            $table->text('system_address')->nullable();
            $table->string('currency')->default('TSh');
            $table->string('currency_symbol')->default('TSh ');
            $table->string('timezone')->default('Africa/Nairobi');
            $table->string('date_format')->default('Y-m-d');
            $table->string('time_format')->default('H:i:s');
            $table->boolean('maintenance_mode')->default(false);
            $table->text('security_flags')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system');
    }
};