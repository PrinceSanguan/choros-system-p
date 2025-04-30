<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ISOOperation;

class ISOOperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operations = [
            [
                'name' => "Operation Safe CALABARZON",
                'region' => "4a",
                'date' => "2023-11-15 08:30:00",
                'team_leader' => "PCol. Juan Dela Cruz",
                'members' => "PCol. Juan Dela Cruz, PMaj. Maria Reyes, PCpt. Carlos Santos",
                'location' => "Batangas City",
                'coordinates' => "13.7563, 121.0583",
                'location_per_day' => "Nov 15 - Batangas City; Nov 16 - Lipa City"
            ],
            [
                'name' => "MIMAROPA Security Sweep",
                'region' => "4b",
                'date' => "2023-12-20 10:00:00",
                'team_leader' => "PCol. Eduardo Lopez",
                'members' => "PCol. Eduardo Lopez, PMaj. Sofia Garcia, PCpt. Miguel Torres",
                'location' => "Puerto Princesa",
                'coordinates' => "9.7392, 118.7353",
                'location_per_day' => "Dec 20 - Puerto Princesa; Dec 21 - El Nido"
            ],
            [
                'name' => "Operation Bicol Safe Streets",
                'region' => "5",
                'date' => "2024-01-05 07:00:00",
                'team_leader' => "PCol. Antonio Bautista",
                'members' => "PCol. Antonio Bautista, PMaj. Lourdes Mendoza, PCpt. Roberto Valencia",
                'location' => "Naga City",
                'coordinates' => "13.6248, 123.1877",
                'location_per_day' => "Jan 5 - Naga City; Jan 6 - Legazpi City"
            ],
            [
                'name' => "CALABARZON Joint Op",
                'region' => "4a",
                'date' => "2024-01-15 09:30:00",
                'team_leader' => "PCol. Ricardo Santos",
                'members' => "PCol. Ricardo Santos, PMaj. Elena Cruz, PCpt. David Reyes",
                'location' => "Cavite",
                'coordinates' => "14.2800, 120.9200",
                'location_per_day' => "Jan 15 - Kawit; Jan 16 - Tagaytay"
            ],
            [
                'name' => "MIMAROPA Community Patrol",
                'region' => "4b",
                'date' => "2023-12-05 08:00:00",
                'team_leader' => "PCol. Manuel Torres",
                'members' => "PCol. Manuel Torres, PMaj. Patricia Gomez, PCpt. Roberto Luna",
                'location' => "Calapan",
                'coordinates' => "13.4105, 121.1817",
                'location_per_day' => "Dec 5 - Calapan; Dec 6 - Naujan"
            ],
            [
                'name' => "Bicol Security Initiative",
                'region' => "5",
                'date' => "2023-02-01 10:00:00",
                'team_leader' => "PCol. Maria Santos",
                'members' => "PCol. Maria Santos, PMaj. Francis Cruz, PCpt. Angelica Reyes",
                'location' => "Legazpi City",
                'coordinates' => "13.1391, 123.7438",
                'location_per_day' => "Feb 1 - Legazpi; Feb 2 - Daraga"
            ],
            [
                'name' => "Southern Tagalog Sweep",
                'region' => "4a",
                'date' => "2024-02-10 07:30:00",
                'team_leader' => "PCol. Jose Garcia",
                'members' => "PCol. Jose Garcia, PMaj. Rosemarie Luna, PCpt. Eduardo Santos",
                'location' => "Laguna",
                'coordinates' => "14.2691, 121.4113",
                'location_per_day' => "Feb 10 - San Pablo; Feb 11 - Los Baños"
            ],
            [
                'name' => "Mindoro Coastal Patrol",
                'region' => "4b",
                'date' => "2023-03-15 09:00:00",
                'team_leader' => "PCol. Roberto Valencia",
                'members' => "PCol. Roberto Valencia, PMaj. Cristina Torres, PCpt. Javier Cruz",
                'location' => "Oriental Mindoro",
                'coordinates' => "12.8362, 121.1227",
                'location_per_day' => "Mar 15 - Puerto Galera; Mar 16 - Roxas"
            ],
            [
                'name' => "Bicol Peace Operation",
                'region' => "5",
                'date' => "2023-04-05 08:30:00",
                'team_leader' => "PCol. Javier Mendoza",
                'members' => "PCol. Javier Mendoza, PMaj. Carmela Santos, PCpt. Victor Torres",
                'location' => "Sorsogon",
                'coordinates' => "12.9748, 124.0047",
                'location_per_day' => "Apr 5 - Sorsogon City; Apr 6 - Bulan"
            ]
        ];

        foreach ($operations as $operation) {
            ISOOperation::create($operation);
        }
    }
}
