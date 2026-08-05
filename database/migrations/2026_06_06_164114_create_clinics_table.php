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
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('logo')->nullable();
            $table->string('image_cover_name')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestampTz('featured_until')->nullable();
            $table->timestamps();

            $table->index('is_featured');
        });

        DB::statement('ALTER TABLE clinics ADD CONSTRAINT chk_clinic_name_not_empty 
    CHECK (TRIM(name) != "")');
        DB::statement('ALTER TABLE clinics ADD CONSTRAINT chk_clinic_slug_not_empty 
    CHECK (TRIM(slug) != "")');
        DB::statement('ALTER TABLE clinics ADD CONSTRAINT chk_latitude_range 
    CHECK (latitude IS NULL OR (latitude BETWEEN -90 AND 90))');
        DB::statement('ALTER TABLE clinics ADD CONSTRAINT chk_longitude_range 
    CHECK (longitude IS NULL OR (longitude BETWEEN -180 AND 180))');
        DB::statement('ALTER TABLE clinics ADD CONSTRAINT chk_coordinates_both_or_none 
    CHECK (
        (latitude IS NULL AND longitude IS NULL) OR
        (latitude IS NOT NULL AND longitude IS NOT NULL)
    )');

    DB::statement('ALTER TABLE clinics ADD CONSTRAINT chk_featured_consistency 
    CHECK (
        is_featured = false OR featured_until IS NOT NULL
    )');

    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
