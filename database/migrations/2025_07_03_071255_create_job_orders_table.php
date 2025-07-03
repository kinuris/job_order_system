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
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('technician_id')->nullable()->constrained()->onDelete('set null');
            
            $table->enum('type', ['installation', 'repair', 'upgrade', 'disconnection', 'maintenance']);
            $table->enum('status', ['pending_dispatch', 'scheduled', 'en_route', 'in_progress', 'on_hold', 'completed', 'cancelled'])->default('pending_dispatch');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

            $table->text('description'); // Initial problem description
            $table->text('resolution_notes')->nullable(); // Notes from the technician on completion

            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_orders');
    }
};
