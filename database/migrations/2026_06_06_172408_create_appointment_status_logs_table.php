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
        Schema::create('appointment_status_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('old_status',['pending','confirmed','completed','cancelled','no_show'])->nullable();
            $table->enum('new_status',['pending','confirmed','completed','cancelled','no_show']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_status_logs');
    }
};
