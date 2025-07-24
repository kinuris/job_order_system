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
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->constrained()->onDelete('set null');
            $table->date('plan_installed_at')->nullable(); // Date the plan was installed
            $table->enum('plan_status', ['active', 'inactive', 'suspended'])->default('active'); // Plan status
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['plan_id', 'plan_installed_at', 'plan_status']);
        });
    }
};
