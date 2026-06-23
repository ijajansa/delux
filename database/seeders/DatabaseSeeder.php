<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default SuperAdmin
        User::firstOrCreate(
            ['email' => 'admin@delux.com'],
            [
                'name' => 'Super Admin',
                'password' => 'admin1234',
                'role' => User::ROLE_SUPERADMIN,
                'is_active' => true,
            ]
        );
    }
}
