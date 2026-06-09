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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->enum('day_of_work', [
                'sunday',
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday'
            ]);
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('slot_duration', [15, 30, 45, 60, 90, 120]);
            $table->time('start_break')->nullable();
            $table->time('end_break')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('price', 8, 2);
            $table->timestamps();
            // $table->unique(['doctor_id', 'day_of_work']);
        });
        DB::statement('ALTER TABLE schedules ADD CONSTRAINT chk_slot_duration 
    CHECK (slot_duration IN (15, 30, 45, 60, 90, 120))');

        DB::statement('ALTER TABLE schedules ADD CONSTRAINT chk_schedule_times 
    CHECK (end_time > start_time)');
        // 1. لو فيه break، لازم الاتنين موجودين مع بعض
        DB::statement('ALTER TABLE schedules ADD CONSTRAINT chk_break_both_or_none 
    CHECK (
        (start_break IS NULL AND end_break IS NULL) OR 
        (start_break IS NOT NULL AND end_break IS NOT NULL)
    )');

        // 2. end_break > start_break
        DB::statement('ALTER TABLE schedules ADD CONSTRAINT chk_break_times 
    CHECK (start_break IS NULL OR end_break > start_break)');

        // 3. الـ break جوه الـ schedule
        DB::statement('ALTER TABLE schedules ADD CONSTRAINT chk_break_within_schedule 
    CHECK (
        start_break IS NULL OR 
        (start_break >= start_time AND end_break <= end_time)
    )');
        DB::statement('ALTER TABLE schedules ADD CONSTRAINT chk_price_positive 
    CHECK (price >= 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
