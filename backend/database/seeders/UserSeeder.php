<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create receptionist user
        User::create([
            'name' => 'Receptionist',
            'email' => 'receptionist@hotel.com',
            'password' => Hash::make('password'),
            'role' => 'receptionist',
        ]);

        // Create maintenance chief user
        User::create([
            'name' => 'Maintenance Chief',
            'email' => 'chief@hotel.com',
            'password' => Hash::make('password'),
            'role' => 'maintenance_chief',
        ]);

        // Create maintenance agents
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "Maintenance Agent $i",
                'email' => "agent$i@hotel.com",
                'password' => Hash::make('password'),
                'role' => 'maintenance_agent',
            ]);
        }
    }
}
