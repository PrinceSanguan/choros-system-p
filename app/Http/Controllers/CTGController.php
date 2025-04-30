<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
    public function index()
    {
        // Fetch all CTGs from the database with their documents
        $ctgs = CTG::all();

        return response()->json([
            'success' => true,
            'data' => $ctgs
        ]);
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
