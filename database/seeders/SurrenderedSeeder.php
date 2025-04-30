<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Surrendered;

class SurrenderedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $surrendered = [
            [
                'name' => "Antonio Cruz",
                'alias' => "Tony",
                'gender' => "male",
                'region' => "4a",
                'province' => "Batangas",
                'municipality' => "Nasugbu",
                'barangay' => "Poblacion",
                'date_of_birth' => "1980-08-25",
                'former_group' => "NPA - Southern Tagalog",
                'date_surrendered' => "2023-06-10 10:00:00",
                'status' => "rehabilitation",
                'remarks' => "Former NPA member who voluntarily surrendered"
            ],
            [
                'name' => "Luzviminda Palawan",
                'alias' => "Luz",
                'gender' => "female",
                'region' => "4b",
                'province' => "Palawan",
                'municipality' => "Puerto Princesa",
                'barangay' => "San Pedro",
                'date_of_birth' => "1988-02-18",
                'former_group' => "NPA - Mindoro Command",
                'date_surrendered' => "2023-06-21 12:30:00",
                'status' => "processing",
                'remarks' => "Surrendered with information about local terrorist activities"
            ],
            [
                'name' => "Roberto Albay",
                'alias' => "Bobby",
                'gender' => "male",
                'region' => "5",
                'province' => "Albay",
                'municipality' => "Legazpi City",
                'barangay' => "Cruzada",
                'date_of_birth' => "1972-06-30",
                'former_group' => "NPA - Bicol Regional Party Committee",
                'date_surrendered' => "2023-06-30 15:45:00",
                'status' => "processing",
                'remarks' => "Provided valuable intel during initial investigation"
            ],
            [
                'name' => "Carmen Salazar",
                'alias' => "Mench",
                'gender' => "female",
                'region' => "4a",
                'province' => "Laguna",
                'municipality' => "Calamba",
                'barangay' => "Barangay 1",
                'date_of_birth' => "1991-05-13",
                'former_group' => "NPA - Southern Tagalog",
                'date_surrendered' => "2023-10-05 14:20:00",
                'status' => "completed",
                'remarks' => "Surrendered with three other associates"
            ],
            [
                'name' => "Eduardo Dimaculangan",
                'alias' => "Ed",
                'gender' => "male",
                'region' => "4b",
                'province' => "Oriental Mindoro",
                'municipality' => "Calapan",
                'barangay' => "San Vicente",
                'date_of_birth' => "1978-11-19",
                'former_group' => "NPA - Mindoro Command",
                'date_surrendered' => "2023-09-15 11:30:00",
                'status' => "rehabilitation",
                'remarks' => "Brought in by family members"
            ],
            [
                'name' => "Maricel Gonzaga",
                'alias' => "Cel",
                'gender' => "female",
                'region' => "5",
                'province' => "Sorsogon",
                'municipality' => "Sorsogon City",
                'barangay' => "Barangay Bibincahan",
                'date_of_birth' => "1985-03-27",
                'former_group' => "NPA - Bicol Regional Party Committee",
                'date_surrendered' => "2023-11-25 08:45:00",
                'status' => "completed",
                'remarks' => "Former rebel organizer"
            ]
        ];

        foreach ($surrendered as $person) {
            Surrendered::create($person);
        }
    }
}
