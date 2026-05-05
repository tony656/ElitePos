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
        // Table to store face encodings for each user
        Schema::create('user_face_encodings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('face_encoding'); // Store face encoding as JSON
            $table->string('device_name')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('registered_at')->useCurrent();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
        });

        // Table to log face verification attempts
        Schema::create('face_verification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('session_id');
            $table->boolean('verification_success')->default(false);
            $table->float('confidence')->nullable(); // Confidence score (0-100)
            $table->text('error_message')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('verified_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['session_id', 'verified_at']);
            $table->index(['user_id', 'verification_success']);
        });

        // Add face recognition settings to system table
        Schema::table('system', function (Blueprint $table) {
            $table->boolean('face_recognition_enabled')->default(false)->after('system_shutdown');
            $table->integer('face_verification_timeout')->default(5)->after('face_recognition_enabled'); // seconds before auto-logout
            $table->boolean('require_face_registration')->default(true)->after('face_verification_timeout');
        });

        // Add face recognition flags to active_sessions table
        Schema::table('active_sessions', function (Blueprint $table) {
            $table->boolean('face_verified')->default(false)->after('is_blocked');
            $table->timestamp('last_face_check')->nullable()->after('face_verified');
            $table->integer('failed_face_attempts')->default(0)->after('last_face_check');
            $table->timestamp('face_verification_expires_at')->nullable()->after('failed_face_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_face_encodings');
        Schema::dropIfExists('face_verification_logs');
        
        Schema::table('system', function (Blueprint $table) {
            $table->dropColumn(['face_recognition_enabled', 'face_verification_timeout', 'require_face_registration']);
        });
        
        Schema::table('active_sessions', function (Blueprint $table) {
            $table->dropColumn(['face_verified', 'last_face_check', 'failed_face_attempts', 'face_verification_expires_at']);
        });
    }
};