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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('start_at');
            $table->timestampTz('end_at');
            $table->enum('status', [
                'active',
                'expired',
                'cancelled',
                'pending'
            ]);
            $table->decimal('price', 10, 2);
            $table->boolean('auto_renew')->default(false);
            $table->timestamps();
            $table->index('status');
        });
        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT chk_subscription_dates 
    CHECK (end_at > start_at)');
        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT chk_subscription_price 
    CHECK (price >= 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
