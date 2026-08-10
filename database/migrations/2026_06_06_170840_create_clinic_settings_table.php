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
        Schema::create('clinicSettings', function (Blueprint $table) {
            $table->id();
            $table->enum('appointment_duration',[15,30,45,60,90,120])->default(30);
            $table->unsignedTinyInteger('cancellation_hours_limit')->default(6);
            $table->boolean('auth_confirm')->default(false);
            $table->json('working_days');
            $table->string('timezone');
            $table->unsignedTinyInteger('deposit_percentage')->default(0);
            $table->unsignedTinyInteger('cancellation_fee_percentage')->default(10);
        });
        DB::statement('ALTER TABLE clinicSettings ADD CONSTRAINT chk_appointment_duration 
    CHECK (appointment_duration IN (15, 30, 45, 60, 90, 120))');

    DB::statement('ALTER TABLE clinicSettings ADD CONSTRAINT chk_deposit_percentage 
    CHECK (deposit_percentage BETWEEN 0 AND 100)');

    DB::statement('ALTER TABLE clinicSettings ADD CONSTRAINT chk_cancellation_fee_percentage 
    CHECK (cancellation_fee_percentage BETWEEN 0 AND 100)');

    DB::statement('ALTER TABLE clinicSettings ADD CONSTRAINT chk_cancellation_hours_limit 
    CHECK (cancellation_hours_limit BETWEEN 1 AND 168)');

    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinicSettings');
    }
};
