<?php
require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get the schema information for the surrendered table
$columns = \Illuminate\Support\Facades\Schema::getColumnListing('surrendered');
echo "Columns in surrendered table:\n";
print_r($columns);

// Get a sample record
$record = \App\Models\Surrendered::first();
echo "\nSample record:\n";
print_r($record ? $record->toArray() : 'No records found');

echo "\nTotal records: " . \App\Models\Surrendered::count() . "\n";
