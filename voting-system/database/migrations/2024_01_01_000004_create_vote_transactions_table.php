<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vote_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('voter_name');
            $table->string('voter_email');
            $table->unsignedInteger('votes');
            $table->unsignedInteger('amount'); // stored in kobo (smallest currency unit)
            $table->enum('payment_method', ['paystack', 'bank_transfer'])->default('paystack');
            $table->enum('payment_status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->enum('verification_status', ['unverified', 'verified', 'rejected'])->default('unverified');
            $table->string('receipt_image')->nullable();
            $table->string('payment_provider_reference')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('payment_status');
            $table->index('payment_method');
            $table->index(['candidate_id', 'payment_status']);
            $table->index('reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vote_transactions');
    }
};
