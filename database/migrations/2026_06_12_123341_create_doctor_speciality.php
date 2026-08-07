<?php

use App\Models\doctor;
use App\Models\speciality;
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
        Schema::create('doctor_speciality', function (Blueprint $table) {
            $table->foreignIdFor(doctor::class)->constrained();
            $table->foreignIdFor(speciality::class)->constrained();

            $table->primary(['doctor_id', 'speciality_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_speciality');
    }
};
