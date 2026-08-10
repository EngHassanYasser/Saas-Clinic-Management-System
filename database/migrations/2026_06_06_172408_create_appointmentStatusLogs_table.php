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
        Schema::create('appointmentStatusLogs', function (Blueprint $table) {
            $table->id();
            $table->enum('old_status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'])->nullable();
            $table->enum('new_status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show']);
            $table->text('reason')->nullable();
            $table->timestamps();
            // $table->index('appointment_id');
            $table->index('new_status');
            $table->index('created_at');
        });
        DB::statement('ALTER TABLE appointmentStatusLogs ADD CONSTRAINT chk_status_changed 
    CHECK (old_status IS NULL OR old_status != new_status)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointmentStatusLogs');
    }
};
