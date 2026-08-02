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
            $table->time('open_time')->nullable()->after('address');
            $table->time('close_time')->nullable()->after('open_time');
        });

        DB::statement("
        ALTER TABLE clinics
        ADD CONSTRAINT chk_clinic_working_hours
        CHECK (
            open_time IS NULL
            OR close_time IS NULL
            OR close_time > open_time
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
            $table->dropColumn(['open_time', 'close_time']);
        });
    }
};
