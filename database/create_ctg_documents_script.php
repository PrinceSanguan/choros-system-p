<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\CTG;
use App\Models\CTGDocument;

// Begin transaction
DB::beginTransaction();

try {
    // First recreate the ctg_documents table
    if (Schema::hasTable('ctg_documents')) {
        Schema::dropIfExists('ctg_documents');
        echo "Dropped existing ctg_documents table\n";
    }

    // Create the ctg_documents table
    Schema::create('ctg_documents', function ($table) {
        $table->id();
        $table->foreignId('ctg_id')->constrained('ctgs')->onDelete('cascade');
        $table->string('file_path');
        $table->string('file_name')->nullable();
        $table->string('file_type')->nullable();
        $table->timestamps();
    });
    echo "Created ctg_documents table\n";

    // Get all CTGs
    $ctgs = CTG::all();
    echo "Found " . $ctgs->count() . " CTG records\n";

    // Sample document types
    $documentTypes = [
        'intelligence_report.pdf' => 'application/pdf',
        'surveillance_photo.jpg' => 'image/jpeg',
        'identification.pdf' => 'application/pdf',
        'activity_log.docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'faction_details.pdf' => 'application/pdf',
        'location_history.xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'network_analysis.pdf' => 'application/pdf',
        'operation_details.pdf' => 'application/pdf'
    ];

    // Add sample documents for each CTG
    $count = 0;
    foreach ($ctgs as $ctg) {
        // Add between 1 and 3 documents for each CTG
        $docCount = rand(1, 3);

        for ($i = 0; $i < $docCount; $i++) {
            // Get a random document type
            $docName = array_rand($documentTypes);
            $docType = $documentTypes[$docName];

            // Create a path (this would normally point to a real file)
            $path = 'ctg-documents/' . uniqid() . '-' . $docName;

            // Create the document record
            $document = new CTGDocument([
                'ctg_id' => $ctg->id,
                'file_path' => $path,
                'file_name' => $docName,
                'file_type' => $docType
            ]);

            $document->save();
            $count++;
        }
    }

    echo "Added $count document records for CTGs\n";

    // Commit transaction
    DB::commit();
    echo "Transaction committed successfully!\n";

} catch (\Exception $e) {
    // Rollback transaction on error
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
    echo "Transaction rolled back\n";
}
