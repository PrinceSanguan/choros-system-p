<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            ISOOperationSeeder::class,
            SightingSeeder::class,
            FirearmSeeder::class,
            CTGSeeder::class,
            PAGSeeder::class,
            SurrenderedSeeder::class,
            SampleSurrenderedSeeder::class,
            SurrenderedDocumentSeeder::class,
        ]);
    }
}
