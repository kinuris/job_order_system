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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Plan name (e.g., "Basic Internet", "Premium Package")
            $table->enum('type', ['internet', 'cable', 'phone', 'bundle']); // Plan type
            $table->text('description')->nullable(); // Plan description
            $table->decimal('monthly_rate', 8, 2); // Monthly cost
            $table->integer('speed_mbps')->nullable(); // For internet plans
            $table->boolean('is_active')->default(true); // Whether plan is available for new customers
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
