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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('start_time');
            $table->timestampTz('end_time');
            $table->enum('status', [
                'pending',
                'confirmed',
                'completed',
                'cancelled',
                'no_show',
                'rescheduled',
                'in_progress'
            ])->default('pending');
            $table->enum('booking_source', ['mobile', 'website']);
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->decimal('deposit_amount', 8, 2)->default(0);
            $table->timestampTz('cancellation_time')->nullable();
            $table->timestampTz('reminder_sent_at');
            $table->timestamps();

            // $table->index(['doctor_id', 'start_time']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
