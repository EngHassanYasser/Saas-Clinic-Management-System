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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EGP');
            $table->enum('payment_method', ['paypal', 'stripe', 'card', 'cash', 'wallet', 'bank_transfer']);
            $table->string('transaction_id')->unique()->nullable();
            $table->enum('status', ['pending', 'paid', 'failed']);
            $table->enum('type', ['deposit', 'refund', 'full_payment']);
            $table->json('gateway_response')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->index('payment_method');
            $table->index('transaction_id');
        });
        DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_payment_amount_positive 
    CHECK (amount > 0)');

        DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_currency_format 
    CHECK (currency REGEXP "^[A-Z]{3}$")');
        DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_failure_reason_status 
    CHECK (status = "failed" OR failure_reason IS NULL)');
        DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_transaction_id_online 
    CHECK (
        payment_method IN ("cash") OR transaction_id IS NOT NULL
    )');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
