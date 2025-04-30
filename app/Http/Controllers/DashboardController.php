<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ISOOperation;
use App\Models\Sighting;
use App\Models\Firearm;
use App\Models\CTG;
use App\Models\PAG;
use App\Models\Surrendered;

class DashboardController extends Controller
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
     * Show the dashboard based on user role.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $regionData = [];

        // Load region-specific data based on user role
        if ($user->isAdmin()) {
            // Admin can see all regions
            $regionData = $this->getAllRegionsData();
        } else {
            // Region-specific user can only see their region data
            switch ($user->username) {
                case 'RMFB4A':
                    $regionData = $this->getRegionData('4a');
                    break;
                case 'RMFB4B':
                    $regionData = $this->getRegionData('4b');
                    break;
                case 'RMFB5':
                    $regionData = $this->getRegionData('5');
                    break;
            }
        }

        return view('dashboard', [
            'user' => $user,
            'regionData' => $regionData
        ]);
    }

    /**
     * Get data for all regions (admin view)
     */
    private function getAllRegionsData()
    {
        return [
            'isoOperations' => $this->getISOOperations(),
            'sightings' => $this->getSightings(),
            'firearms' => $this->getFirearms(),
            'ctgs' => $this->getCTGs(),
            'pags' => $this->getPAGs(),
            'surrendered' => $this->getSurrendered()
        ];
    }

    /**
     * Get data for a specific region
     */
    private function getRegionData($region)
    {
        return [
            'isoOperations' => $this->getISOOperations($region),
            'sightings' => $this->getSightings($region),
            'firearms' => $this->getFirearms($region),
            'ctgs' => $this->getCTGs($region),
            'pags' => $this->getPAGs($region),
            'surrendered' => $this->getSurrendered($region)
        ];
    }

    /**
     * Get ISO operations data
     */
    private function getISOOperations($region = null)
    {
        $query = ISOOperation::query();

        if ($region) {
            $query->where('region', $region);
        }

        return $query->orderBy('date', 'desc')->get()->toArray();
    }

    /**
     * Get sightings data
     */
    private function getSightings($region = null)
    {
        $query = Sighting::query();

        if ($region) {
            $query->where('region', $region);
        }

        return $query->orderBy('date', 'desc')->get()->toArray();
    }

    /**
     * Get firearms data
     */
    private function getFirearms($region = null)
    {
        $query = Firearm::query();

        if ($region) {
            $query->where('region', $region);
        }

        return $query->orderBy('date', 'desc')->get()->toArray();
    }

    /**
     * Get CTGs data
     */
    private function getCTGs($region = null)
    {
        $query = CTG::query();

        if ($region) {
            $query->where('region', $region);
        }

        return $query->orderBy('created_at', 'desc')->get()->toArray();
    }

    /**
     * Get PAGs data
     */
    private function getPAGs($region = null)
    {
        $query = PAG::query();

        if ($region) {
            $query->where('region', $region);
        }

        return $query->orderBy('created_at', 'desc')->get()->toArray();
    }

    /**
     * Get surrendered data
     */
    private function getSurrendered($region = null)
    {
        $query = Surrendered::query();

        if ($region) {
            $query->where('region', $region);
        }

        return $query->orderBy('date_surrendered', 'desc')->get()->toArray();
    }

    /**
     * Get ISO operations by month for chart
     */
    public function getISOOperationsByMonth()
    {
        $user = Auth::user();
        $region = null;

        if (!$user->isAdmin()) {
            switch ($user->username) {
                case 'RMFB4A':
                    $region = '4a';
                    break;
                case 'RMFB4B':
                    $region = '4b';
                    break;
                case 'RMFB5':
                    $region = '5';
                    break;
            }
        }

        $months = ['Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr'];
        $counts = [];

        $query = ISOOperation::query();
        if ($region) {
            $query->where('region', $region);
        }

        $operations = $query->get();

        foreach ($months as $month) {
            $monthNumber = date('m', strtotime($month));
            $count = $operations->filter(function($op) use ($monthNumber) {
                return date('m', strtotime($op->date)) == $monthNumber;
            })->count();

            $counts[] = $count;
        }

        return response()->json([
            'labels' => $months,
            'data' => $counts
        ]);
    }

    /**
     * Get regional distribution data for chart
     */
    public function getRegionalDistribution()
    {
        $regions = ['4a', '4b', '5'];
        $labels = [
            'Region 4A (CALABARZON)',
            'Region 4B (MIMAROPA)',
            'Region 5 (Bicol)'
        ];

        $data = [];

        foreach ($regions as $index => $region) {
            $count = 0;
            $count += ISOOperation::where('region', $region)->count();
            $count += Sighting::where('region', $region)->count();
            $count += Firearm::where('region', $region)->count();
            $count += CTG::where('region', $region)->count();
            $count += PAG::where('region', $region)->count();
            $count += Surrendered::where('region', $region)->count();

            $data[] = $count;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }

    /**
     * Store a new CTG member record
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCTG(Request $request)
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

        // For now, just return a success response
        // In a real application, this would save to a database
        return response()->json([
            'success' => true,
            'message' => 'CTG record saved successfully',
        ]);
    }
}
