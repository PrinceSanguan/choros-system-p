<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'APCSL Administrator',
            'username' => 'APCSLADMIN',
            'email' => 'admin@pnp-apcsl.gov.ph',
            'password' => Hash::make('APCSL'),
            'role' => 'admin',
        ]);

        // Create Region 4A user
        User::create([
            'name' => 'Region 4A Officer',
            'username' => 'RMFB4A',
            'email' => 'rmfb4a@pnp-apcsl.gov.ph',
            'password' => Hash::make('APCSL'),
            'role' => 'user',
        ]);

        // Create Region 4B user
        User::create([
            'name' => 'Region 4B Officer',
            'username' => 'RMFB4B',
            'email' => 'rmfb4b@pnp-apcsl.gov.ph',
            'password' => Hash::make('APCSL'),
            'role' => 'user',
        ]);

        // Create Region 5 user
        User::create([
            'name' => 'Region 5 Officer',
            'username' => 'RMFB5',
            'email' => 'rmfb5@pnp-apcsl.gov.ph',
            'password' => Hash::make('APCSL'),
            'role' => 'user',
        ]);
    }
}
