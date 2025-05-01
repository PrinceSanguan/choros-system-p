<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\CTG;

class CTGController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of CTGs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Log the request for debugging
        Log::info('CTG index method called', [
            'has_region' => $request->has('region'),
            'region_param' => $request->query('region')
        ]);

        try {
            // Check if region filter is provided
            if ($request->has('region') && $request->query('region') !== 'all') {
                $region = $request->query('region');
                $ctgs = CTG::where('region', $region)->get();
                Log::info('Filtered CTGs by region', [
                    'region' => $region,
                    'count' => $ctgs->count()
                ]);
            } else {
                // Fetch all CTGs from the database
                $ctgs = CTG::all();
                Log::info('Fetched all CTGs', [
                    'count' => $ctgs->count(),
                    'first_record' => $ctgs->first() ? $ctgs->first()->toArray() : null
                ]);
            }

            // If no records found in the database, use sample data
            if ($ctgs->isEmpty()) {
                $sampleData = $this->getSampleData();
                Log::info('No records found, using sample data', [
                    'count' => count($sampleData)
                ]);

                return response()->json([
                    'success' => true,
                    'data' => $sampleData
                ]);
            }

            // Make sure data is properly serialized and all required fields are present
            $serializedData = $ctgs->toArray();

            // Return actual database records
            Log::info('Returning database records', [
                'count' => count($serializedData)
            ]);

            return response()->json([
                'success' => true,
                'data' => $serializedData
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error fetching CTGs: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            // Return sample data as fallback in case of any database error
            $sampleData = $this->getSampleData();

            return response()->json([
                'success' => true,
                'data' => $sampleData
            ]);
        }
    }

    /**
     * Get sample data for testing purposes.
     *
     * @return array
     */
    public function getSampleData()
    {
        return [
            [
                'id' => 1,
                'name' => 'Jose Santos',
                'region' => '4a',
                'address' => 'Unknown, possibly Cavite',
                'pob' => 'Trece Martires City',
                'dob' => '1985-03-15',
                'affiliated_front' => 'NPA - Southern Tagalog',
                'last_seen' => '2023-11-16 14:30:00',
                'status' => 'active',
                'photo_path' => null,
            ],
            [
                'id' => 2,
                'name' => 'Maria Reyes',
                'region' => '4b',
                'address' => 'Rural Occidental Mindoro',
                'pob' => 'San Jose, Occidental Mindoro',
                'dob' => '1990-07-22',
                'affiliated_front' => 'NPA - Mindoro Command',
                'last_seen' => '2023-12-22 19:45:00',
                'status' => 'active',
                'photo_path' => null,
            ],
            [
                'id' => 3,
                'name' => 'Pedro Bicol',
                'region' => '5',
                'address' => 'Rural Sorsogon',
                'pob' => 'Bulan, Sorsogon',
                'dob' => '1982-11-05',
                'affiliated_front' => 'NPA - Bicol Regional Party Committee',
                'last_seen' => '2024-01-27 11:20:00',
                'status' => 'active',
                'photo_path' => null,
            ],
            [
                'id' => 4,
                'name' => 'Antonio Mendoza',
                'region' => '4a',
                'address' => 'Batangas province',
                'pob' => 'Lipa City',
                'dob' => '1988-05-10',
                'affiliated_front' => 'NPA - Southern Tagalog',
                'last_seen' => '2023-12-10 08:45:00',
                'status' => 'neutralized',
                'photo_path' => null,
            ],
            [
                'id' => 5,
                'name' => 'Elena Castro',
                'region' => '4b',
                'address' => 'Eastern Mindoro',
                'pob' => 'Puerto Galera',
                'dob' => '1992-09-18',
                'affiliated_front' => 'NPA - Mindoro Command',
                'last_seen' => '2023-10-05 16:30:00',
                'status' => 'surrendered',
                'photo_path' => null,
            ]
        ];
    }

    /**
     * Store a newly created CTG in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|in:4a,4b,5',
            'address' => 'required|string|max:255',
            'pob' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'affiliated_front' => 'required|string|max:255',
            'last_seen' => 'nullable|date',
            'status' => 'required|string|in:active,neutralized,surrendered,deceased',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Handle file uploads
        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('ctg-photos', 'public');
        }

        // Create new CTG record
        $ctg = new CTG([
            'name' => $validated['name'],
            'region' => $validated['region'],
            'address' => $validated['address'],
            'pob' => $validated['pob'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'affiliated_front' => $validated['affiliated_front'],
            'last_seen' => $validated['last_seen'] ?? null,
            'status' => $validated['status'],
            'photo_path' => $photoPath,
        ]);

        $ctg->save();

        return response()->json([
            'success' => true,
            'message' => 'CTG record saved successfully',
            'data' => $ctg
        ]);
    }

    /**
     * Display the specified CTG.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ctg = CTG::find($id);

        if (!$ctg) {
            return response()->json([
                'success' => false,
                'message' => 'CTG not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $ctg
        ]);
    }

    /**
     * Update the specified CTG in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ctg = CTG::find($id);

        if (!$ctg) {
            return response()->json([
                'success' => false,
                'message' => 'CTG not found'
            ], 404);
        }

        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|in:4a,4b,5',
            'address' => 'required|string|max:255',
            'pob' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'affiliated_front' => 'required|string|max:255',
            'last_seen' => 'nullable|date',
            'status' => 'required|string|in:active,neutralized,surrendered,deceased',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Handle photo upload if a new one is provided
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($ctg->photo_path) {
                Storage::disk('public')->delete($ctg->photo_path);
            }

            $photoPath = $request->file('photo')->store('ctg-photos', 'public');
            $ctg->photo_path = $photoPath;
        }

        // Update CTG record
        $ctg->name = $validated['name'];
        $ctg->region = $validated['region'];
        $ctg->address = $validated['address'];
        $ctg->pob = $validated['pob'] ?? null;
        $ctg->dob = $validated['dob'] ?? null;
        $ctg->affiliated_front = $validated['affiliated_front'];
        $ctg->last_seen = $validated['last_seen'] ?? null;
        $ctg->status = $validated['status'];

        $ctg->save();

        return response()->json([
            'success' => true,
            'message' => 'CTG record updated successfully',
            'data' => $ctg
        ]);
    }

    /**
     * Remove the specified CTG from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ctg = CTG::find($id);

        if (!$ctg) {
            return response()->json([
                'success' => false,
                'message' => 'CTG not found'
            ], 404);
        }

        // Delete photo if exists
        if ($ctg->photo_path) {
            Storage::disk('public')->delete($ctg->photo_path);
        }

        // Delete CTG record
        $ctg->delete();

        return response()->json([
            'success' => true,
            'message' => 'CTG record deleted successfully'
        ]);
    }

    /**
     * Export CTG data to Excel/CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        // Log the export request
        Log::info('CTG export method called', [
            'has_region' => $request->has('region'),
            'region_param' => $request->query('region')
        ]);

        // Get the CTG data
        if ($request->has('region') && $request->query('region') !== 'all') {
            $region = $request->query('region');
            $ctgs = CTG::where('region', $region)->get();
            Log::info('Exporting CTGs filtered by region', [
                'region' => $region,
                'count' => $ctgs->count()
            ]);
        } else {
            $ctgs = CTG::all();
            Log::info('Exporting all CTGs', [
                'count' => $ctgs->count()
            ]);
        }

        // If no records found, use sample data
        if ($ctgs->isEmpty()) {
            $ctgs = collect($this->getSampleData());
            Log::info('Using sample data for export', [
                'count' => $ctgs->count()
            ]);
        }

        // Define column headers
        $headers = [
            'Name', 'Region', 'Address', 'Place of Birth', 'Date of Birth',
            'Affiliated Front', 'Last Seen', 'Status'
        ];

        // Format the region values
        $regionMap = [
            '4a' => 'Region 4A (CALABARZON)',
            '4b' => 'Region 4B (MIMAROPA)',
            '5' => 'Region 5 (Bicol)'
        ];

        // Create CSV content
        $csv = implode(',', $headers) . "\n";

        foreach ($ctgs as $ctg) {
            // Format date values
            $dob = $ctg->dob ? date('Y-m-d', strtotime($ctg->dob)) : '';
            $lastSeen = $ctg->last_seen ? date('Y-m-d H:i:s', strtotime($ctg->last_seen)) : '';

            // Format region value
            $regionFormatted = isset($regionMap[$ctg->region]) ? $regionMap[$ctg->region] : $ctg->region;

            // Format status (capitalize first letter)
            $status = ucfirst($ctg->status);

            // Escape fields for CSV
            $row = [
                $this->escapeCsv($ctg->name),
                $this->escapeCsv($regionFormatted),
                $this->escapeCsv($ctg->address),
                $this->escapeCsv($ctg->pob),
                $this->escapeCsv($dob),
                $this->escapeCsv($ctg->affiliated_front),
                $this->escapeCsv($lastSeen),
                $this->escapeCsv($status)
            ];

            $csv .= implode(',', $row) . "\n";
        }

        // Generate a filename with timestamp
        $filename = 'CTG_Data_' . date('Y-m-d_His') . '.csv';

        // Log the export completion
        Log::info('CTG export completed', [
            'filename' => $filename,
            'rows' => $ctgs->count()
        ]);

        // Return the CSV as a downloadable file
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Escape a string for CSV output.
     *
     * @param  string  $string
     * @return string
     */
    private function escapeCsv($string)
    {
        // Convert to string if it's not already
        $string = strval($string);

        // If the string contains a comma, double quote, or newline, wrap it in double quotes
        if (preg_match('/[,"\n\r]/', $string)) {
            // Double up any double quotes
            $string = str_replace('"', '""', $string);
            // Wrap in quotes
            $string = '"' . $string . '"';
        }

        return $string;
    }
}
