<?php

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
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator Yayasan',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => true,
                'color_theme' => 'emerald',
            ],
        );
    }
}
