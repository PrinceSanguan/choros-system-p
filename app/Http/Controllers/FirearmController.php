<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Firearm;

class FirearmController extends Controller
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
     * Display a listing of firearms.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Filter by region if specified
        $region = $request->query('region');
        $search = $request->query('search');

        $query = Firearm::query();

        if ($region && $region != 'all') {
            $query->where('region', $region);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('type', 'like', "%{$search}%")
                  ->orWhere('caliber', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('surrendered_by', 'like', "%{$search}%");
            });
        }

        $firearms = $query->orderBy('date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $firearms
        ]);
    }

    /**
     * Store a newly created firearm in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'region' => 'required|string|in:4a,4b,5',
            'type' => 'required|string|max:255',
            'caliber' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'surrendered_by' => 'nullable|string|max:255',
            'weapons' => 'nullable|string',
        ]);

        // Create new Firearm record
        $firearm = new Firearm($validated);
        $firearm->save();

        return response()->json([
            'success' => true,
            'message' => 'Firearm saved successfully',
            'data' => $firearm
        ]);
    }

    /**
     * Display the specified firearm.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $firearm = Firearm::find($id);

        if (!$firearm) {
            return response()->json([
                'success' => false,
                'message' => 'Firearm not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $firearm
        ]);
    }

    /**
     * Update the specified firearm in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find the Firearm record
        $firearm = Firearm::find($id);

        if (!$firearm) {
            return response()->json([
                'success' => false,
                'message' => 'Firearm not found'
            ], 404);
        }

        // Validate the incoming request
        $validated = $request->validate([
            'region' => 'required|string|in:4a,4b,5',
            'type' => 'required|string|max:255',
            'caliber' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'surrendered_by' => 'nullable|string|max:255',
            'weapons' => 'nullable|string',
        ]);

        // Update Firearm record
        $firearm->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Firearm updated successfully',
            'data' => $firearm
        ]);
    }

    /**
     * Remove the specified firearm from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $firearm = Firearm::find($id);

        if (!$firearm) {
            return response()->json([
                'success' => false,
                'message' => 'Firearm not found'
            ], 404);
        }

        // Delete Firearm record
        $firearm->delete();

        return response()->json([
            'success' => true,
            'message' => 'Firearm deleted successfully'
        ]);
    }
}
