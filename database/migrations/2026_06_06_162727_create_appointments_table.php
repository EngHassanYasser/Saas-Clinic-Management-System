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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', [
                'pending',
                'confirmed',
                'completed',
                'cancelled',
                'no_show',
                'rescheduled',
                'in_progress'
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->decimal('deposit_amount', 8, 2)->default(0);
            $table->timestampTz('cancellation_time')->nullable();
            $table->timestampTz('reminder_sent_at')->nullable();
            $table->index('status');
        });
        DB::statement('ALTER TABLE appointments ADD CONSTRAINT chk_appointment_times 
    CHECK (end_time > start_time)');
        DB::statement('ALTER TABLE appointments ADD CONSTRAINT chk_deposit_positive 
    CHECK (deposit_amount >= 0)');
        DB::statement('ALTER TABLE appointments ADD CONSTRAINT chk_cancellation_consistency 
    CHECK (
        (cancellation_reason IS NULL AND cancellation_time IS NULL) OR
        (cancellation_reason IS NOT NULL AND cancellation_time IS NOT NULL)
    )');
        DB::statement('ALTER TABLE appointments ADD CONSTRAINT chk_cancellation_after_start 
    CHECK (cancellation_time IS NULL OR cancellation_time >= start_time)');

        DB::statement('ALTER TABLE appointments ADD CONSTRAINT chk_cancellation_reason_status 
    CHECK (
        status = "cancelled" OR cancellation_reason IS NULL
    )');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
