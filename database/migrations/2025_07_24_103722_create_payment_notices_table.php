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
        Schema::create('payment_notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->date('due_date');
            $table->date('period_from'); // Billing period start
            $table->date('period_to'); // Billing period end
            $table->decimal('amount_due', 10, 2);
            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->date('paid_at')->nullable();
            $table->integer('days_overdue')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['customer_id', 'due_date']);
            $table->index(['status', 'due_date']);
            $table->index('days_overdue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_notices');
    }
};
