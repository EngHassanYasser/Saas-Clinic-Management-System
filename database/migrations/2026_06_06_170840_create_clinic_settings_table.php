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
        Schema::create('clinic_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('appointment_duration')->default(30);
            $table->unsignedTinyInteger('cancellation_hours_limit')->default(6);
            $table->boolean('auth_confirm')->default(false);
            $table->json('working_days');
            $table->string('timezone');
            $table->unsignedTinyInteger('deposit_percentage')->default(0);
            $table->unsignedTinyInteger('cancellation_fee_percentage')->default(10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_settings');
    }
};
