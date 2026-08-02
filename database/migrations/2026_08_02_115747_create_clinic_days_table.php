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
        Schema::create('clinic_day', function (Blueprint $table) {
            $table->foreignId('clinic_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('day_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['clinic_id', 'day_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_day');
    }
};
