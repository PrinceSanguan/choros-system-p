<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Firearm;

class FirearmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firearms = [
            [
                'region' => "4a",
                'type' => "M16 Rifle",
                'caliber' => "5.56mm",
                'date' => "2023-06-17 09:15:00",
                'location' => "Calamba, Laguna",
                'surrendered_by' => "Unknown (recovered)",
                'weapons' => null
            ],
            [
                'region' => "4b",
                'type' => "9mm Pistol",
                'caliber' => "9mm",
                'date' => "2023-06-23 15:30:00",
                'location' => "Romblon",
                'surrendered_by' => "John Doe (voluntary)",
                'weapons' => null
            ],
            [
                'region' => "5",
                'type' => "Shotgun",
                'caliber' => "12 gauge",
                'date' => "2023-06-28 13:45:00",
                'location' => "Camalig, Albay",
                'surrendered_by' => "Carlos Reyes (voluntary)",
                'weapons' => null
            ]
        ];

        // Clear existing records
        Firearm::truncate();

        // Insert new records
        foreach ($firearms as $firearm) {
            Firearm::create($firearm);
        }
    }
}
