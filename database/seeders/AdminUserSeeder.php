<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists to avoid duplicates
        $adminExists = User::where('username', 'admin')
                          ->orWhere('email', 'admin@2dinein.com')
                          ->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'System Administrator',
                'email' => 'admin@2dinein.com',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '1234567890',
                'address' => 'Admin Office, Main Branch',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Admin user created successfully!');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}