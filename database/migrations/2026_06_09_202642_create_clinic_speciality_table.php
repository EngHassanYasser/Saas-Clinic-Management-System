<?php

use App\Models\clinic;
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
        Schema::create('clinic_speciality', function (Blueprint $table) {
            $table->foreignIdFor(clinic::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(speciality::class)->constrained()->cascadeOnDelete();   
             $table->primary(['clinic_id', 'speciality_id']); 
              $table->index('speciality_id');       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_speciality');
    }
};
