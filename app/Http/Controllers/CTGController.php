<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\CTG;
use App\Models\CTGDocument;

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

        // Check if region filter is provided
        if ($request->has('region')) {
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
                'count' => $ctgs->count()
            ]);
        }

        // If no records found, return static sample data for testing
        if ($ctgs->isEmpty()) {
            $ctgs = $this->getSampleData();
            Log::info('Using sample data for testing', [
                'count' => count($ctgs)
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $ctgs
        ]);
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
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|max:10240',
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

        // Handle document uploads if any
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('ctg-documents', 'public');

                // Create document record
                $ctgDocument = new CTGDocument([
                    'ctg_id' => $ctg->id,
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'file_type' => $document->getClientMimeType(),
                ]);

                $ctgDocument->save();
            }
        }

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
        $ctg = CTG::with('documents')->find($id);

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
        // Find the CTG record
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
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|max:10240',
        ]);

        // Handle photo upload if provided
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

        // Handle document uploads if any
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('ctg-documents', 'public');

                // Create document record
                $ctgDocument = new CTGDocument([
                    'ctg_id' => $ctg->id,
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'file_type' => $document->getClientMimeType(),
                ]);

                $ctgDocument->save();
            }
        }

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
        $ctg = CTG::with('documents')->find($id);

        if (!$ctg) {
            return response()->json([
                'success' => false,
                'message' => 'CTG not found'
            ], 404);
        }

        // Delete associated documents and files
        foreach ($ctg->documents as $document) {
            Storage::disk('public')->delete($document->file_path);
            $document->delete();
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
}
