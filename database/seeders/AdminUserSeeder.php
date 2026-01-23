<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        if (!User::where('email', 'admin@havenhue.com')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@havenhue.com',
                'password' => Hash::make('password'), // You can change this
                'role' => 'admin',
                'email_verified_at' => Carbon::now(),
            ]);
            $this->command->info('Admin user created successfully.');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
