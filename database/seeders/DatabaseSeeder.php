<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the AdminUserSeeder which will clear all data and create only 1 admin and 1 technician
        $this->call(AdminUserSeeder::class);
        
        // Seed plans
        $this->call(PlanSeeder::class);
    }
}
