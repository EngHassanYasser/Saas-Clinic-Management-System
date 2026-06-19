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
        Schema::create('doctor_clinic_prices', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->foreignId('doctor_id')->constrained();
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('appointment_type_id')->constrained();
            $table->decimal('price', 8, 2);
            $table->timestamps();

            $table->unique(
                ['doctor_id', 'clinic_id', 'appointment_type_id'],
                'dcp_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_clinic_prices');
    }
};
