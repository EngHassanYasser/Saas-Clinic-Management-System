<?php

use App\Models\notification;
use App\Models\User;
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
        Schema::table('notificationLogs', function (Blueprint $table) {
            $table->foreignIdFor(user::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(notification::class)->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notificationLogs', function (Blueprint $table) {
            //
        });
    }
};
