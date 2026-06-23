<?php

use App\Models\day;
use App\Models\schedule;
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
        Schema::create('day_schedule', function (Blueprint $table) {
            $table->foreignIdFor(day::class)->constrained();
            $table->foreignIdFor(schedule::class)->constrained();

            $table->primary(['day_id', 'schedule_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_schedule');
    }
};
