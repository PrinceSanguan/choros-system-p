<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sighting;

class SightingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sightings = [
            [
                'region' => "4a",
                'date' => "2023-06-16 14:30:00",
                'location' => "Tanauan, Batangas",
                'coordinates' => "14.0867, 121.1495",
                'description' => "Suspected CTG members seen in rural barangay."
            ],
            [
                'region' => "4b",
                'date' => "2023-06-22 19:45:00",
                'location' => "San Jose, Occidental Mindoro",
                'coordinates' => "12.3526, 121.0668",
                'description' => "Report of armed men near coastal area."
            ],
            [
                'region' => "5",
                'date' => "2023-06-27 11:20:00",
                'location' => "Sorsogon City",
                'coordinates' => "12.9738, 124.0010",
                'description' => "Vehicle matching description of known PAG member."
            ]
        ];

        // Clear existing records
        Sighting::truncate();

        // Insert new records
        foreach ($sightings as $sighting) {
            Sighting::create($sighting);
        }
    }
}
