<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin Digika',
                'email' => 'admin@digika.com',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Marketing Manager',
                'email' => 'marketing@digika.com',
                'role' => 'marketing',
                'password' => Hash::make('market123'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Visualisateur',
                'email' => 'viewer@digika.com',
                'role' => 'viewer',
                'password' => Hash::make('viewer123'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}