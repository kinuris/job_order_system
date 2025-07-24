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
            // Make plan_status nullable and remove default
            $table->enum('plan_status', ['active', 'inactive', 'suspended'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Restore original non-nullable with default
            $table->enum('plan_status', ['active', 'inactive', 'suspended'])->default('active')->change();
        });
    }
};
