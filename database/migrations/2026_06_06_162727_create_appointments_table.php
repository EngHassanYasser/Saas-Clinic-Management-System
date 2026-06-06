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
            $table->enum('status',['pending','confirmed','completed','cancelled','no_show']);
            $table->enum('booking_source',['mobile','website']);
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->decimal('deposit_amount')->default(0);
            $table->timestampTz('cancellation_time')->nullable();
            $table->boolean('is_reminder_sent')->default(false);
            $table->timestamps();
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
