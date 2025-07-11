<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Technician;
use App\Models\Customer;
use App\Models\JobOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Clean up all existing data first
        $this->cleanupAllData();

        // Create the admin user
        $adminUser = User::create([
            'name' => 'System Administrator',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create the technician user
        $techUser = User::create([
            'name' => 'Tech User',
            'username' => 'tech1',
            'password' => Hash::make('password'),
            'role' => 'technician',
        ]);

        // Create technician profile for the technician user
        Technician::create([
            'user_id' => $techUser->id,
            'phone_number' => '+1234567890',
        ]);

        $this->command->info('Database seeded with 1 admin and 1 technician user.');
    }

    /**
     * Clean up all existing data.
     */
    private function cleanupAllData(): void
    {
        // Disable foreign key checks to allow truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Remove all data
        JobOrder::truncate();
        Customer::truncate();
        Technician::truncate();
        User::truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Cleared all existing records from database.');
    }
}
