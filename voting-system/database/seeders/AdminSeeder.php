<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@nacovote.com'],
            [
                'name' => 'NACOS Admin',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
            ]
        );
    }
}
