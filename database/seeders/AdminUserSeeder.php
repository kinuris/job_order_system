<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Technician;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create an admin user
        $adminUser = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'System Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create a technician user
        $techUser = User::firstOrCreate(
            ['username' => 'tech1'],
            [
                'name' => 'John Technician',
                'username' => 'tech1',
                'password' => Hash::make('password'),
                'role' => 'technician',
            ]
        );

        // Create technician profile for the technician user
        Technician::firstOrCreate(
            ['user_id' => $techUser->id],
            [
                'user_id' => $techUser->id,
                'phone_number' => '+1234567890',
            ]
        );
    }
}
