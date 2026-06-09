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
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('channel', [
                'email',
                'sms',
                'database',
                'broadcast'
            ]);

            $table->enum('status', [
                'pending',
                'sent',
                'delivered',
                'failed'
            ])->default('pending');
            $table->json('response')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE notification_logs ADD CONSTRAINT chk_delivered_after_created 
    CHECK (status = "pending" OR updated_at >= created_at)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
