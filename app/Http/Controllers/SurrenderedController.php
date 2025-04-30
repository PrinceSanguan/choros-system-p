<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Surrendered;
use App\Models\SurrenderedDocument;

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
}
