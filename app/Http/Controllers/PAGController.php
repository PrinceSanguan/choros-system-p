<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PAG;
use App\Models\PAGDocument;

class PAGController extends Controller
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
     * Display a listing of PAG members.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Filter by region if specified
        $region = $request->query('region');
        $search = $request->query('search');

        $query = PAG::query();

        if ($region && $region != 'all') {
            $query->where('region', $region);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('affiliation', 'like', "%{$search}%");
            });
        }

        $pags = $query->orderBy('last_seen', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $pags
        ]);
    }

    /**
     * Store a newly created PAG member in database.
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
            'affiliation' => 'required|string|max:255',
            'last_seen' => 'nullable|date',
            'status' => 'required|string|in:active,neutralized,surrendered,deceased',
            'photo' => 'nullable|image|max:2048',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|max:10240',
        ]);

        // Handle photo upload
        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('pag-photos', 'public');
        }

        // Create new PAG record
        $pag = new PAG([
            'name' => $validated['name'],
            'region' => $validated['region'],
            'address' => $validated['address'],
            'pob' => $validated['pob'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'affiliation' => $validated['affiliation'],
            'last_seen' => $validated['last_seen'] ?? null,
            'status' => $validated['status'],
            'photo_path' => $photoPath,
        ]);

        $pag->save();

        // Handle document uploads if any
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('pag-documents', 'public');

                // Create document record
                $pagDocument = new PAGDocument([
                    'pag_id' => $pag->id,
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'file_type' => $document->getClientMimeType(),
                ]);

                $pagDocument->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'PAG member saved successfully',
            'data' => $pag
        ]);
    }

    /**
     * Display the specified PAG member.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pag = PAG::with('documents')->find($id);

        if (!$pag) {
            return response()->json([
                'success' => false,
                'message' => 'PAG member not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pag
        ]);
    }

    /**
     * Update the specified PAG member in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find the PAG record
        $pag = PAG::find($id);

        if (!$pag) {
            return response()->json([
                'success' => false,
                'message' => 'PAG member not found'
            ], 404);
        }

        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|in:4a,4b,5',
            'address' => 'required|string|max:255',
            'pob' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'affiliation' => 'required|string|max:255',
            'last_seen' => 'nullable|date',
            'status' => 'required|string|in:active,neutralized,surrendered,deceased',
            'photo' => 'nullable|image|max:2048',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|max:10240',
        ]);

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($pag->photo_path) {
                Storage::disk('public')->delete($pag->photo_path);
            }

            $photoPath = $request->file('photo')->store('pag-photos', 'public');
            $pag->photo_path = $photoPath;
        }

        // Update PAG record
        $pag->name = $validated['name'];
        $pag->region = $validated['region'];
        $pag->address = $validated['address'];
        $pag->pob = $validated['pob'] ?? null;
        $pag->dob = $validated['dob'] ?? null;
        $pag->affiliation = $validated['affiliation'];
        $pag->last_seen = $validated['last_seen'] ?? null;
        $pag->status = $validated['status'];

        $pag->save();

        // Handle document uploads if any
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('pag-documents', 'public');

                // Create document record
                $pagDocument = new PAGDocument([
                    'pag_id' => $pag->id,
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'file_type' => $document->getClientMimeType(),
                ]);

                $pagDocument->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'PAG member updated successfully',
            'data' => $pag
        ]);
    }

    /**
     * Remove the specified PAG member from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pag = PAG::with('documents')->find($id);

        if (!$pag) {
            return response()->json([
                'success' => false,
                'message' => 'PAG member not found'
            ], 404);
        }

        // Delete associated documents and files
        foreach ($pag->documents as $document) {
            Storage::disk('public')->delete($document->file_path);
            $document->delete();
        }

        // Delete photo if exists
        if ($pag->photo_path) {
            Storage::disk('public')->delete($pag->photo_path);
        }

        // Delete PAG record
        $pag->delete();

        return response()->json([
            'success' => true,
            'message' => 'PAG member deleted successfully'
        ]);
    }
}
