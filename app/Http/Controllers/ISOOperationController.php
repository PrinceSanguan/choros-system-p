<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ISOOperation;

class ISOOperationController extends Controller
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
     * Display a listing of ISO operations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Filter by region if specified
        $region = $request->query('region');
        $search = $request->query('search');

        $query = ISOOperation::query();

        if ($region && $region != 'all') {
            $query->where('region', $region);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('team_leader', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $operations = $query->orderBy('date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $operations
        ]);
    }

    /**
     * Store a newly created ISO operation in database.
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
            'date' => 'required|date',
            'team_leader' => 'required|string|max:255',
            'members' => 'nullable|string',
            'location' => 'required|string|max:255',
            'coordinates' => 'nullable|string|max:255',
            'location_per_day' => 'nullable|string',
        ]);

        // Create new ISO Operation record
        $isoOperation = new ISOOperation($validated);
        $isoOperation->save();

        return response()->json([
            'success' => true,
            'message' => 'ISO Operation saved successfully',
            'data' => $isoOperation
        ]);
    }

    /**
     * Display the specified ISO operation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $isoOperation = ISOOperation::find($id);

        if (!$isoOperation) {
            return response()->json([
                'success' => false,
                'message' => 'ISO Operation not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $isoOperation
        ]);
    }

    /**
     * Update the specified ISO operation in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find the ISO Operation record
        $isoOperation = ISOOperation::find($id);

        if (!$isoOperation) {
            return response()->json([
                'success' => false,
                'message' => 'ISO Operation not found'
            ], 404);
        }

        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|in:4a,4b,5',
            'date' => 'required|date',
            'team_leader' => 'required|string|max:255',
            'members' => 'nullable|string',
            'location' => 'required|string|max:255',
            'coordinates' => 'nullable|string|max:255',
            'location_per_day' => 'nullable|string',
        ]);

        // Update ISO Operation record
        $isoOperation->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'ISO Operation updated successfully',
            'data' => $isoOperation
        ]);
    }

    /**
     * Remove the specified ISO operation from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $isoOperation = ISOOperation::find($id);

        if (!$isoOperation) {
            return response()->json([
                'success' => false,
                'message' => 'ISO Operation not found'
            ], 404);
        }

        // Delete ISO Operation record
        $isoOperation->delete();

        return response()->json([
            'success' => true,
            'message' => 'ISO Operation deleted successfully'
        ]);
    }
}
