<?php

use App\Models\appointment;
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
        Schema::table('appointmentStatusLogs', function (Blueprint $table) {
            $table->foreignIdFor(appointment::class)->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointmentStatusLogs', function (Blueprint $table) {
            //
        });
    }
};
