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
        // Create Admin
        User::create([
            'name' => 'Admin Eventify',
            'email' => 'admin@eventify.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Create Approved Organizer
        User::create([
            'name' => 'Event Organizer',
            'email' => 'organizer@eventify.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'organizer_status' => 'approved',
            'phone' => '081234567891',
        ]);

        // Create Pending Organizer
        User::create([
            'name' => 'Pending Organizer',
            'email' => 'pending@eventify.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'organizer_status' => 'pending',
            'phone' => '081234567892',
        ]);

        // Create Rejected Organizer
        User::create([
            'name' => 'Rejected Organizer',
            'email' => 'rejected@eventify.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'organizer_status' => 'rejected',
            'rejection_reason' => 'Incomplete documentation provided.',
            'phone' => '081234567893',
        ]);

        // Create Regular User
        User::create([
            'name' => 'John Doe',
            'email' => 'user@eventify.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567894',
        ]);

        // Create more regular users
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567895',
        ]);

        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567896',
        ]);
    }
}
