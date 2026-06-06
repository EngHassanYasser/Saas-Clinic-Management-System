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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
