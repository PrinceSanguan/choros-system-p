<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sighting;

class SightingController extends Controller
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
     * Display a listing of sightings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Filter by region if specified
        $region = $request->query('region');
        $search = $request->query('search');

        $query = Sighting::query();

        if ($region && $region != 'all') {
            $query->where('region', $region);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sightings = $query->orderBy('date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $sightings
        ]);
    }

    /**
     * Store a newly created sighting in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'region' => 'required|string|in:4a,4b,5',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'coordinates' => 'nullable|string|max:255',
            'description' => 'required|string',
        ]);

        // Create new Sighting record
        $sighting = new Sighting($validated);
        $sighting->save();

        return response()->json([
            'success' => true,
            'message' => 'Sighting saved successfully',
            'data' => $sighting
        ]);
    }

    /**
     * Display the specified sighting.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sighting = Sighting::find($id);

        if (!$sighting) {
            return response()->json([
                'success' => false,
                'message' => 'Sighting not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sighting
        ]);
    }

    /**
     * Update the specified sighting in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find the Sighting record
        $sighting = Sighting::find($id);

        if (!$sighting) {
            return response()->json([
                'success' => false,
                'message' => 'Sighting not found'
            ], 404);
        }

        // Validate the incoming request
        $validated = $request->validate([
            'region' => 'required|string|in:4a,4b,5',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'coordinates' => 'nullable|string|max:255',
            'description' => 'required|string',
        ]);

        // Update Sighting record
        $sighting->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sighting updated successfully',
            'data' => $sighting
        ]);
    }

    /**
     * Remove the specified sighting from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sighting = Sighting::find($id);

        if (!$sighting) {
            return response()->json([
                'success' => false,
                'message' => 'Sighting not found'
            ], 404);
        }

        // Delete Sighting record
        $sighting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sighting deleted successfully'
        ]);
    }
}
