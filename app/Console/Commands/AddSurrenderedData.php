<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Surrendered;
use Illuminate\Support\Facades\DB;

class AddSurrenderedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-surrendered-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add sample data to the surrendered table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding sample data to surrendered table...');

        // Begin transaction
        DB::beginTransaction();

        try {
            // Clear existing data if requested
            if ($this->confirm('Do you want to clear existing data from the surrendered table?', false)) {
                Surrendered::truncate();
                $this->info('Existing data cleared.');
            }

            // Sample data for surrendered individuals
            $data = [
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

            // Insert the data
            $bar = $this->output->createProgressBar(count($data));
            $bar->start();

            foreach ($data as $record) {
                Surrendered::create($record);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();

            // Commit the transaction
            DB::commit();
            $this->info('Successfully added ' . count($data) . ' records to the surrendered table.');

        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();
            $this->error('Error: ' . $e->getMessage());
            $this->warn('Transaction rolled back.');
            return 1;
        }

        return 0;
    }
}
