<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('system', function (Blueprint $table) {
            $table->string('theme_color')->default('var(--theme-primary)')->after('system_shutdown');
            $table->text('theme_colors')->nullable()->after('theme_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system', function (Blueprint $table) {
            $table->dropColumn(['theme_color', 'theme_colors']);
        });
    }
};
