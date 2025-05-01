<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Surrendered;
use App\Models\SurrenderedDocument;
use Illuminate\Support\Facades\Log;

class SurrenderedController extends Controller
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
     * Display a listing of surrendered individuals.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Filter by region if specified
        $region = $request->query('region');
        $search = $request->query('search');

        $query = Surrendered::query();

        if ($region && $region != 'all') {
            $query->where('region', $region);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $surrendereds = $query->orderBy('date_surrendered', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $surrendereds
        ]);
    }

    /**
     * Store a newly created surrendered individual in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255',
            'gender' => 'required|string|in:male,female',
            'date_surrendered' => 'required|date',
            'date_of_birth' => 'nullable|date',
            'former_group' => 'required|string|max:255',
            'region' => 'required|string|in:4a,4b,5',
            'province' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'status' => 'required|string|in:rehabilitation,processing,completed',
            'remarks' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|max:10240',
        ]);

        // Handle photo upload
        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('surrendered-photos', 'public');
        }

        // Create new surrendered record
        $surrendered = new Surrendered([
            'name' => $validated['name'],
            'alias' => $validated['alias'],
            'gender' => $validated['gender'],
            'date_surrendered' => $validated['date_surrendered'],
            'date_of_birth' => $validated['date_of_birth'],
            'former_group' => $validated['former_group'],
            'region' => $validated['region'],
            'province' => $validated['province'],
            'municipality' => $validated['municipality'],
            'barangay' => $validated['barangay'],
            'status' => $validated['status'],
            'remarks' => $validated['remarks'],
            'photo_path' => $photoPath,
        ]);

        $surrendered->save();

        // Handle document uploads if any
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('surrendered-documents', 'public');

                // Create document record
                $surrenderedDocument = new SurrenderedDocument([
                    'surrendered_id' => $surrendered->id,
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'file_type' => $document->getClientMimeType(),
                ]);

                $surrenderedDocument->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Surrendered record saved successfully',
            'data' => $surrendered
        ]);
    }

    /**
     * Display the specified surrendered individual.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $surrendered = Surrendered::with('documents')->find($id);

        if (!$surrendered) {
            return response()->json([
                'success' => false,
                'message' => 'Surrendered record not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $surrendered
        ]);
    }

    /**
     * Update the specified surrendered individual in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find the surrendered record
        $surrendered = Surrendered::find($id);

        if (!$surrendered) {
            return response()->json([
                'success' => false,
                'message' => 'Surrendered record not found'
            ], 404);
        }

        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255',
            'gender' => 'required|string|in:male,female',
            'date_surrendered' => 'required|date',
            'date_of_birth' => 'nullable|date',
            'former_group' => 'required|string|max:255',
            'region' => 'required|string|in:4a,4b,5',
            'province' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'status' => 'required|string|in:rehabilitation,processing,completed',
            'remarks' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|max:10240',
        ]);

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($surrendered->photo_path) {
                Storage::disk('public')->delete($surrendered->photo_path);
            }

            $photoPath = $request->file('photo')->store('surrendered-photos', 'public');
            $surrendered->photo_path = $photoPath;
        }

        // Update surrendered record
        $surrendered->name = $validated['name'];
        $surrendered->alias = $validated['alias'];
        $surrendered->gender = $validated['gender'];
        $surrendered->date_surrendered = $validated['date_surrendered'];
        $surrendered->date_of_birth = $validated['date_of_birth'];
        $surrendered->former_group = $validated['former_group'];
        $surrendered->region = $validated['region'];
        $surrendered->province = $validated['province'];
        $surrendered->municipality = $validated['municipality'];
        $surrendered->barangay = $validated['barangay'];
        $surrendered->status = $validated['status'];
        $surrendered->remarks = $validated['remarks'];

        $surrendered->save();

        // Handle document uploads if any
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('surrendered-documents', 'public');

                // Create document record
                $surrenderedDocument = new SurrenderedDocument([
                    'surrendered_id' => $surrendered->id,
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'file_type' => $document->getClientMimeType(),
                ]);

                $surrenderedDocument->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Surrendered record updated successfully',
            'data' => $surrendered
        ]);
    }

    /**
     * Remove the specified surrendered individual from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $surrendered = Surrendered::with('documents')->find($id);

        if (!$surrendered) {
            return response()->json([
                'success' => false,
                'message' => 'Surrendered record not found'
            ], 404);
        }

        // Delete associated documents and files
        foreach ($surrendered->documents as $document) {
            Storage::disk('public')->delete($document->file_path);
            $document->delete();
        }

        // Delete photo if exists
        if ($surrendered->photo_path) {
            Storage::disk('public')->delete($surrendered->photo_path);
        }

        // Delete surrendered record
        $surrendered->delete();

        return response()->json([
            'success' => true,
            'message' => 'Surrendered record deleted successfully'
        ]);
    }

    /**
     * Export Surrendered data to Excel/CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        // Log the export request
        Log::info('Surrendered export method called', [
            'has_region' => $request->has('region'),
            'region_param' => $request->query('region')
        ]);

        // Get the Surrendered data
        if ($request->has('region') && $request->query('region') !== 'all') {
            $region = $request->query('region');
            $surrendered = Surrendered::where('region', $region)->get();
            Log::info('Exporting Surrendered filtered by region', [
                'region' => $region,
                'count' => $surrendered->count()
            ]);
        } else {
            $surrendered = Surrendered::all();
            Log::info('Exporting all Surrendered', [
                'count' => $surrendered->count()
            ]);
        }

        // Define column headers
        $headers = [
            'Name', 'Region', 'Date of Birth', 'Place of Birth', 'Former Group',
            'Date Surrendered', 'Status', 'Notes'
        ];

        // Format the region values
        $regionMap = [
            '4a' => 'Region 4A (CALABARZON)',
            '4b' => 'Region 4B (MIMAROPA)',
            '5' => 'Region 5 (Bicol)'
        ];

        // Create CSV content
        $csv = implode(',', $headers) . "\n";

        foreach ($surrendered as $person) {
            // Format date values
            $dob = $person->date_of_birth ? date('Y-m-d', strtotime($person->date_of_birth)) : '';
            $dateSurrendered = $person->date_surrendered ? date('Y-m-d', strtotime($person->date_surrendered)) : '';

            // Format region value
            $regionFormatted = isset($regionMap[$person->region]) ? $regionMap[$person->region] : $person->region;

            // Format status (capitalize first letter)
            $status = ucfirst($person->status);

            // Escape fields for CSV
            $row = [
                $this->escapeCsv($person->name),
                $this->escapeCsv($regionFormatted),
                $this->escapeCsv($dob),
                $this->escapeCsv($person->place_of_birth),
                $this->escapeCsv($person->former_group),
                $this->escapeCsv($dateSurrendered),
                $this->escapeCsv($status),
                $this->escapeCsv($person->notes)
            ];

            $csv .= implode(',', $row) . "\n";
        }

        // Generate a filename with timestamp
        $filename = 'Surrendered_Data_' . date('Y-m-d_His') . '.csv';

        // Log the export completion
        Log::info('Surrendered export completed', [
            'filename' => $filename,
            'rows' => $surrendered->count()
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
        $string = strval($string ?? '');

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
