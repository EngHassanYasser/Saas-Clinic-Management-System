<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->time('openTime')->nullable()->after('address');
            $table->time('closeTime')->nullable()->after('openTime');
        });

        DB::statement("
        ALTER TABLE clinics
        ADD CONSTRAINT chk_clinic_working_hours
        CHECK (
            openTime IS NULL
            OR closeTime IS NULL
            OR closeTime > openTime
        )
    ");
    }

    public function down(): void
    {
        DB::statement("
        ALTER TABLE clinics
        DROP CHECK chk_clinic_working_hours
    ");

        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn(['openTime', 'closeTime']);
        });
    }
};
