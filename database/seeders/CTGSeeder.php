<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CTG;

class CTGSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ctgs = [
            [
                'name' => "Jose Santos",
                'region' => "4a",
                'address' => "Unknown, possibly Cavite",
                'pob' => "Trece Martires City",
                'dob' => "1985-03-15",
                'affiliated_front' => "NPA - Southern Tagalog",
                'last_seen' => "2023-11-16 14:30:00",
                'status' => "active"
            ],
            [
                'name' => "Maria Reyes",
                'region' => "4b",
                'address' => "Rural Occidental Mindoro",
                'pob' => "San Jose, Occidental Mindoro",
                'dob' => "1990-07-22",
                'affiliated_front' => "NPA - Mindoro Command",
                'last_seen' => "2023-12-22 19:45:00",
                'status' => "active"
            ],
            [
                'name' => "Pedro Bicol",
                'region' => "5",
                'address' => "Rural Sorsogon",
                'pob' => "Bulan, Sorsogon",
                'dob' => "1982-11-05",
                'affiliated_front' => "NPA - Bicol Regional Party Committee",
                'last_seen' => "2024-01-27 11:20:00",
                'status' => "active"
            ],
            [
                'name' => "Antonio Mendoza",
                'region' => "4a",
                'address' => "Batangas province",
                'pob' => "Lipa City",
                'dob' => "1988-05-10",
                'affiliated_front' => "NPA - Southern Tagalog",
                'last_seen' => "2023-12-10 08:45:00",
                'status' => "neutralized"
            ],
            [
                'name' => "Elena Castro",
                'region' => "4b",
                'address' => "Eastern Mindoro",
                'pob' => "Puerto Galera",
                'dob' => "1992-09-18",
                'affiliated_front' => "NPA - Mindoro Command",
                'last_seen' => "2023-10-05 16:30:00",
                'status' => "surrendered"
            ],
            [
                'name' => "Rafael Gonzales",
                'region' => "5",
                'address' => "Camarines Sur",
                'pob' => "Naga City",
                'dob' => "1979-12-03",
                'affiliated_front' => "NPA - Bicol Regional Party Committee",
                'last_seen' => "2023-11-20 14:15:00",
                'status' => "deceased"
            ],
            [
                'name' => "Sofia Bautista",
                'region' => "4a",
                'address' => "Rizal province",
                'pob' => "Antipolo",
                'dob' => "1993-07-15",
                'affiliated_front' => "NPA - Southern Tagalog",
                'last_seen' => "2024-01-05 17:20:00",
                'status' => "active"
            ],
            [
                'name' => "Manuel Dimaculangan",
                'region' => "4b",
                'address' => "Occidental Mindoro highlands",
                'pob' => "Mamburao",
                'dob' => "1981-02-28",
                'affiliated_front' => "NPA - Mindoro Command",
                'last_seen' => "2023-09-12 11:40:00",
                'status' => "surrendered"
            ],
            [
                'name' => "Cristina Aguilar",
                'region' => "5",
                'address' => "Albay province",
                'pob' => "Legazpi City",
                'dob' => "1995-04-19",
                'affiliated_front' => "NPA - Bicol Regional Party Committee",
                'last_seen' => "2023-10-28 09:35:00",
                'status' => "active"
            ]
        ];

        foreach ($ctgs as $ctg) {
            CTG::create($ctg);
        }
    }
}
