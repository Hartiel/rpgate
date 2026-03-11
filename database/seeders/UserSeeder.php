<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create first admin user
        User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@rpgate.com',
            'password' => bcrypt('admin123'),
            'role' => UserRole::ADMIN,
        ]);

        // Create random users
        User::factory(10)->create();
    }
}
