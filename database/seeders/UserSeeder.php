<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin account
        User::create([
            'name' => 'Admin',
            'username' => 'APCSLADMIN',
            'email' => 'admin@pnp-emapping.com',
            'password' => Hash::make('APCSL'),
            'role' => 'admin',
        ]);

        // User accounts
        User::create([
            'name' => 'User Region 4A',
            'username' => 'RMFB4A',
            'email' => 'user4a@pnp-emapping.com',
            'password' => Hash::make('APCSL'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'User Region 4B',
            'username' => 'RMFB4B',
            'email' => 'user4b@pnp-emapping.com',
            'password' => Hash::make('APCSL'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'User Region 5',
            'username' => 'RMFB5',
            'email' => 'user5@pnp-emapping.com',
            'password' => Hash::make('APCSL'),
            'role' => 'user',
        ]);
    }
}
