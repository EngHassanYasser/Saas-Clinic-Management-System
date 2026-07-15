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
            $table->foreignId('doctor_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('patient_name', 50)->nullable();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete();
            $table->enum('department', [
                'radiology',
                'reception',
                'laboratory',
                'pharmacy',
                'accounting',
                'customer_service',
                'nursing',
                'administration',
                'clinics',
                'technical_support',
            ]);
            $table->date('visit_date');
            $table->enum('severity', [
                'low',
                'medium',
                'high',
                'critical',
            ]);
            $table->enum('issue_type', [
                'complaint',
                'suggestion',
                'technical_issue',
                'billing',
                'medical',
                'other',
            ]);
            $table->string('description', 500);
            $table->enum('status', ['pending', 'under_review', 'resolved', 'rejected'])->default('pending');
            $table->string('resolution_notes', 500)->nullable();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            $table->index(['clinic_id', 'status']);
            $table->index('department');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
