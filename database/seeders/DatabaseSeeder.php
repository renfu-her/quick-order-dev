<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user for Filament
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@quickorder.com',
            'password' => Hash::make('password'),
        ]);

        // Seed sample data
        $this->call([
            MemberSeeder::class,
            ProductSeeder::class,
            AdSeeder::class,
            CouponSeeder::class,
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('=== Admin Credentials (Backend) ===');
        $this->command->info('Email: admin@quickorder.com');
        $this->command->info('Password: password');
        $this->command->info('');
        $this->command->info('=== Member Credentials (Frontend) ===');
        $this->command->info('Email: member@test.com');
        $this->command->info('Password: password');
    }
}
