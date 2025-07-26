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
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('plan_rate', 10, 2); // Store the plan rate at time of payment
            $table->date('payment_date');
            $table->date('period_from'); // Start of billing period this payment covers
            $table->date('period_to'); // End of billing period this payment covers
            $table->enum('payment_method', ['cash', 'bank_transfer', 'gcash', 'paymaya', 'check', 'other']);
            $table->string('reference_number')->nullable(); // For digital payments
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('confirmed');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['customer_id', 'payment_date']);
            $table->index(['period_from', 'period_to']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_payments');
    }
};
