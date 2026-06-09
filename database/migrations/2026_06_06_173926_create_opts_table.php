<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10);
            $table->timestampTz('verified_at')->nullable();
            $table->timestampTz('expired_at');
            $table->boolean('is_used')->default(false);
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();
            $table->index('expired_at');
        });

        DB::statement('ALTER TABLE otps ADD CONSTRAINT chk_otp_expiry 
    CHECK (expired_at > created_at)');
        DB::statement('ALTER TABLE otps ADD CONSTRAINT chk_otp_attempts 
    CHECK (attempts BETWEEN 0 AND 10)');
        DB::statement('ALTER TABLE otps ADD CONSTRAINT chk_verified_is_used 
    CHECK (verified_at IS NULL OR is_used = true)');
        DB::statement('ALTER TABLE otps ADD CONSTRAINT chk_otp_code_not_empty 
    CHECK (TRIM(code) != "")');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opts');
    }
};
