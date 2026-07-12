<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complains', function (Blueprint $table) {
            $table->id();

            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('target_type',['doctor', 'clinic'])->nullable()->default(null); // 'doctor' | 'clinic' | null
            $table->unsignedBigInteger('target_id')->nullable();

            $table->string('subject');
            $table->text('description');
            $table->enum('category', ['service_quality', 'wait_time', 'billing', 'staff_behavior', 'medical_concern', 'facility', 'other'])->default('other');
            $table->enum('status',['pending', 'under_review', 'resolved', 'rejected'])->default('pending');

            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            $table->index(['clinic_id', 'status']);
            $table->index(['target_type', 'target_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
