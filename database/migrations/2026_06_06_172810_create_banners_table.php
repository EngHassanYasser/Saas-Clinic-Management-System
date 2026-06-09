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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image')->nullable();
            $table->text('redirect_url')->nullable();
            $table->timestampTz('start_at')->nullable();
            $table->timestampTz('end_at')->nullable();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->index('is_active');
            $table->index(['start_at', 'end_at']);
            // $table->index('clinic_id');
        });
        DB::statement('ALTER TABLE banners ADD CONSTRAINT chk_banner_dates 
    CHECK (end_at IS NULL OR start_at IS NULL OR end_at > start_at)');

    DB::statement('ALTER TABLE banners ADD CONSTRAINT chk_banner_dates_both_or_none 
    CHECK (
        (start_at IS NULL AND end_at IS NULL) OR
        (start_at IS NOT NULL AND end_at IS NOT NULL)
    )');

    DB::statement('ALTER TABLE banners ADD CONSTRAINT chk_banner_title_not_empty 
    CHECK (TRIM(title) != "")');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
