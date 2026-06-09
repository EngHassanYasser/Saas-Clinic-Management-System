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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                'appointment_confirmed',
                'appointment_cancelled',
                'appointment_reminder',
                'appointment_rescheduled',
                'payment_received',
                'refund_processed',
            ]);
            $table->string('title');
            $table->text('body');
            $table->timestampTz('sent_at')->nullable();
            $table->timestampTz('read_at')->nullable();
            $table->timestamps();
            // $table->index(['user_id', 'is_read']);
            $table->index('type');
            $table->index('created_at');
        });
        DB::statement('ALTER TABLE notifications ADD CONSTRAINT chk_read_after_sent 
    CHECK (read_at IS NULL OR sent_at IS NULL OR read_at >= sent_at)');
        DB::statement('ALTER TABLE notifications ADD CONSTRAINT chk_notification_title_not_empty 
    CHECK (TRIM(title) != "")');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
