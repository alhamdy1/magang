<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@perizinan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Create Kabid user
        User::create([
            'name' => 'Kabid Penyelenggaraan',
            'email' => 'kabid@perizinan.com',
            'password' => Hash::make('password'),
            'role' => 'kabid',
            'phone' => '081234567891',
        ]);

        // Create Kasi user
        User::create([
            'name' => 'Kasi Perijinan',
            'email' => 'kasi@perizinan.com',
            'password' => Hash::make('password'),
            'role' => 'kasi',
            'phone' => '081234567892',
        ]);

        // Create Operator users
        User::create([
            'name' => 'Operator 1',
            'email' => 'operator1@perizinan.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'phone' => '081234567893',
        ]);

        User::create([
            'name' => 'Operator 2',
            'email' => 'operator2@perizinan.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'phone' => '081234567894',
        ]);

        // Create a test user (applicant)
        User::create([
            'name' => 'Test User',
            'email' => 'user@perizinan.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567895',
        ]);
    }
}
