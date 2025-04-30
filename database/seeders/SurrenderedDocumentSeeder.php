<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Surrendered;
use App\Models\SurrenderedDocument;
use Illuminate\Support\Str;

class SurrenderedDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all surrendered individuals
        $surrenderedPersons = Surrendered::all();

        if ($surrenderedPersons->isEmpty()) {
            $this->command->info('No surrendered individuals found. Skipping document creation.');
            return;
        }

        $this->command->info('Creating documents for ' . $surrenderedPersons->count() . ' surrendered individuals');

        // Document types with their MIME types and document type descriptions
        $documentTypes = [
            'intelligence_report.pdf' => [
                'mime' => 'application/pdf',
                'type' => 'Intelligence Report',
                'size' => rand(100000, 5000000)
            ],
            'profile_photo.jpg' => [
                'mime' => 'image/jpeg',
                'type' => 'Profile Photo',
                'size' => rand(50000, 2000000)
            ],
            'identification.pdf' => [
                'mime' => 'application/pdf',
                'type' => 'Identification Document',
                'size' => rand(100000, 3000000)
            ],
            'background_check.docx' => [
                'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'type' => 'Background Check Report',
                'size' => rand(80000, 1500000)
            ],
            'interview_transcript.pdf' => [
                'mime' => 'application/pdf',
                'type' => 'Interview Transcript',
                'size' => rand(200000, 4000000)
            ],
            'history_record.xlsx' => [
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'type' => 'Historical Record',
                'size' => rand(50000, 1000000)
            ],
            'surrender_certificate.pdf' => [
                'mime' => 'application/pdf',
                'type' => 'Surrender Certificate',
                'size' => rand(100000, 2000000)
            ],
            'rehabilitation_plan.pdf' => [
                'mime' => 'application/pdf',
                'type' => 'Rehabilitation Plan',
                'size' => rand(150000, 3000000)
            ]
        ];

        $documentsAdded = 0;

        // Add documents for each surrendered person
        foreach ($surrenderedPersons as $person) {
            // Each person gets 1-3 random documents
            $docCount = rand(1, 3);

            for ($i = 0; $i < $docCount; $i++) {
                // Select a random document type
                $docName = array_rand($documentTypes);
                $docInfo = $documentTypes[$docName];

                // Create a unique file path (in a real app, this would point to an actual file)
                $uniqueId = Str::random(10);
                $path = 'surrendered-documents/' . $uniqueId . '-' . $docName;

                // Create the document record with all required fields
                $document = new SurrenderedDocument();
                $document->surrendered_id = $person->id;
                $document->document_type = $docInfo['type'];
                $document->file_path = $path;
                $document->filename = $uniqueId . '-' . $docName;
                $document->original_filename = $docName;
                $document->mime_type = $docInfo['mime'];
                $document->file_size = $docInfo['size'];

                // Keep these for backward compatibility
                $document->file_name = $docName;
                $document->file_type = $docInfo['mime'];

                $document->save();
                $documentsAdded++;
            }
        }

        $this->command->info("Added {$documentsAdded} documents for surrendered individuals");
    }
}
