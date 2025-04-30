<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PAG;

class PAGSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pags = [
            [
                'name' => "Ricardo Gomez",
                'region' => "4a",
                'address' => "Binan, Laguna",
                'pob' => "Binan, Laguna",
                'dob' => "1978-05-30",
                'affiliation' => "Unknown criminal group",
                'last_seen' => "2023-11-18 21:00:00",
                'status' => "active"
            ],
            [
                'name' => "Luisa Fernandez",
                'region' => "4b",
                'address' => "Roxas, Oriental Mindoro",
                'pob' => "Roxas, Oriental Mindoro",
                'dob' => "1985-09-14",
                'affiliation' => "Local armed group",
                'last_seen' => "2023-12-24 18:30:00",
                'status' => "active"
            ],
            [
                'name' => "Domingo Bicolano",
                'region' => "5",
                'address' => "Daraga, Albay",
                'pob' => "Daraga, Albay",
                'dob' => "1975-12-10",
                'affiliation' => "Local militia",
                'last_seen' => "2024-01-29 14:15:00",
                'status' => "active"
            ],
            [
                'name' => "Fernando Santos",
                'region' => "4a",
                'address' => "Cavite City",
                'pob' => "Cavite City",
                'dob' => "1982-07-15",
                'affiliation' => "Criminal syndicate",
                'last_seen' => "2023-10-05 19:45:00",
                'status' => "neutralized"
            ],
            [
                'name' => "Margarita Ramos",
                'region' => "4b",
                'address' => "Calapan, Oriental Mindoro",
                'pob' => "Calapan, Oriental Mindoro",
                'dob' => "1990-03-28",
                'affiliation' => "Local armed faction",
                'last_seen' => "2023-11-12 20:30:00",
                'status' => "surrendered"
            ],
            [
                'name' => "Roberto Magtanggol",
                'region' => "5",
                'address' => "Naga City, Camarines Sur",
                'pob' => "Naga City",
                'dob' => "1969-09-09",
                'affiliation' => "Private armed group",
                'last_seen' => "2023-12-01 15:20:00",
                'status' => "active"
            ],
            [
                'name' => "Teresa Bautista",
                'region' => "4a",
                'address' => "Santa Rosa, Laguna",
                'pob' => "Santa Rosa, Laguna",
                'dob' => "1988-11-12",
                'affiliation' => "Political private army",
                'last_seen' => "2024-02-03 13:40:00",
                'status' => "active"
            ]
        ];

        foreach ($pags as $pag) {
            PAG::create($pag);
        }
    }
}
