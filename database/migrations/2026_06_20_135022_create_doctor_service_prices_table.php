<?php

use App\Models\clinic;
use App\Models\DoctorService;
use App\Models\doctor;
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
        Schema::create('doctor_service_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Clinic::class)->constrained();
            $table->foreignIdFor(Doctor::class)->constrained();
            $table->foreignIdFor(DoctorService::class)->constrained();
            $table->string('description',500);
            $table->decimal('price', 8, 2)->check('price' > 0);

            $table->timestamps();

            $table->unique(
                ['clinic_id', 'doctor_id', 'doctorService_id'],
                'dsp_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_doctorService_prices');
    }
};
