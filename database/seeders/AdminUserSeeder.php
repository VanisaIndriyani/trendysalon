<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@trendysalon.com'],
            [
                'name' => 'Admin Trendy Salon',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
            ]
        );
    }
}