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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('monthly_price', 10, 2);
            $table->unsignedSmallInteger('max_doctors');
            $table->unsignedSmallInteger('monthly_appointments_limit');
            $table->json('features')->nullable();
        });
        DB::statement('ALTER TABLE plans ADD CONSTRAINT chk_plan_price_positive 
    CHECK (monthly_price >= 0)');
    DB::statement('ALTER TABLE plans ADD CONSTRAINT chk_max_doctors_positive 
    CHECK (max_doctors > 0)');
    DB::statement('ALTER TABLE plans ADD CONSTRAINT chk_appointments_limit_positive 
    CHECK (monthly_appointments_limit > 0)');
    DB::statement('ALTER TABLE plans ADD CONSTRAINT chk_plan_name_not_empty 
    CHECK (TRIM(name) != "")');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
