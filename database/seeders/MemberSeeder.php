<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'name' => 'Test Member',
                'email' => 'member@test.com',
                'phone' => '+1 (555) 123-4567',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+1 (555) 234-5678',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '+1 (555) 345-6789',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
        ];

        foreach ($members as $memberData) {
            Member::create($memberData);
        }
    }
}

