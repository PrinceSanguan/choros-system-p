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
use App\Models\User;

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
        if ($user->role === 'admin') {
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
    public function getAllRegionsData()
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

        if ($user->role !== 'admin') {
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
     * Get map data for Google Maps integration
     */
    public function getMapData(Request $request)
    {
        $user = Auth::user();
        $regionFilter = $request->query('region', 'all');
        $categoryFilter = $request->query('category', 'all');

        // Initialize array to hold all map data
        $mapData = [];

        // Apply region filter if not "all"
        $regionCondition = ($regionFilter !== 'all' && in_array($regionFilter, ['4a', '4b', '5']))
            ? function($query) use ($regionFilter) { return $query->where('region', $regionFilter); }
            : function($query) { return $query; };

        // Apply user role restrictions
        if ($user->role !== 'admin') {
            $userRegion = null;
            switch ($user->username) {
                case 'RMFB4A':
                    $userRegion = '4a';
                    break;
                case 'RMFB4B':
                    $userRegion = '4b';
                    break;
                case 'RMFB5':
                    $userRegion = '5';
                    break;
            }

            if ($userRegion) {
                $regionCondition = function($query) use ($userRegion) {
                    return $query->where('region', $userRegion);
                };
            }
        }

        // Get data based on category filter or get all if "all"
        if ($categoryFilter === 'all' || $categoryFilter === 'ctgs') {
            $ctgs = CTG::where(function($query) use ($regionCondition) {
                return $regionCondition($query);
            })->get();

            foreach ($ctgs as $ctg) {
                // Here we would need to geocode the address if coordinates aren't stored
                // For this example, we'll use placeholder coordinates
                $lat = $this->getRandomCoordinate(13, 14);
                $lng = $this->getRandomCoordinate(120, 123);

                if ($ctg->region === '4a') {
                    $lat = $this->getRandomCoordinate(13.9, 14.2);
                    $lng = $this->getRandomCoordinate(120.8, 121.1);
                } elseif ($ctg->region === '4b') {
                    $lat = $this->getRandomCoordinate(12.8, 13.3);
                    $lng = $this->getRandomCoordinate(120.9, 121.5);
                } elseif ($ctg->region === '5') {
                    $lat = $this->getRandomCoordinate(13.0, 13.7);
                    $lng = $this->getRandomCoordinate(123.2, 123.8);
                }

                $mapData[] = [
                    'lat' => $lat,
                    'lng' => $lng,
                    'type' => 'ctgs',
                    'region' => $ctg->region,
                    'title' => $ctg->name,
                    'description' => $ctg->affiliated_front,
                    'id' => $ctg->id,
                    'status' => $ctg->status
                ];
            }
        }

        if ($categoryFilter === 'all' || $categoryFilter === 'iso') {
            $operations = ISOOperation::where(function($query) use ($regionCondition) {
                return $regionCondition($query);
            })->get();

            foreach ($operations as $operation) {
                // Parse coordinates if stored or use placeholder
                $lat = $this->getRandomCoordinate(13, 14);
                $lng = $this->getRandomCoordinate(120, 123);

                if ($operation->region === '4a') {
                    $lat = $this->getRandomCoordinate(13.9, 14.2);
                    $lng = $this->getRandomCoordinate(120.8, 121.1);
                } elseif ($operation->region === '4b') {
                    $lat = $this->getRandomCoordinate(12.8, 13.3);
                    $lng = $this->getRandomCoordinate(120.9, 121.5);
                } elseif ($operation->region === '5') {
                    $lat = $this->getRandomCoordinate(13.0, 13.7);
                    $lng = $this->getRandomCoordinate(123.2, 123.8);
                }

                $mapData[] = [
                    'lat' => $lat,
                    'lng' => $lng,
                    'type' => 'iso',
                    'region' => $operation->region,
                    'title' => $operation->name,
                    'description' => $operation->location,
                    'id' => $operation->id
                ];
            }
        }

        if ($categoryFilter === 'all' || $categoryFilter === 'sightings') {
            $sightings = Sighting::where(function($query) use ($regionCondition) {
                return $regionCondition($query);
            })->get();

            foreach ($sightings as $sighting) {
                $lat = $this->getRandomCoordinate(13, 14);
                $lng = $this->getRandomCoordinate(120, 123);

                if ($sighting->region === '4a') {
                    $lat = $this->getRandomCoordinate(13.9, 14.2);
                    $lng = $this->getRandomCoordinate(120.8, 121.1);
                } elseif ($sighting->region === '4b') {
                    $lat = $this->getRandomCoordinate(12.8, 13.3);
                    $lng = $this->getRandomCoordinate(120.9, 121.5);
                } elseif ($sighting->region === '5') {
                    $lat = $this->getRandomCoordinate(13.0, 13.7);
                    $lng = $this->getRandomCoordinate(123.2, 123.8);
                }

                $mapData[] = [
                    'lat' => $lat,
                    'lng' => $lng,
                    'type' => 'sightings',
                    'region' => $sighting->region,
                    'title' => 'Sighting: ' . substr($sighting->description, 0, 30) . '...',
                    'description' => $sighting->location,
                    'id' => $sighting->id
                ];
            }
        }

        if ($categoryFilter === 'all' || $categoryFilter === 'firearms') {
            $firearms = Firearm::where(function($query) use ($regionCondition) {
                return $regionCondition($query);
            })->get();

            foreach ($firearms as $firearm) {
                $lat = $this->getRandomCoordinate(13, 14);
                $lng = $this->getRandomCoordinate(120, 123);

                if ($firearm->region === '4a') {
                    $lat = $this->getRandomCoordinate(13.9, 14.2);
                    $lng = $this->getRandomCoordinate(120.8, 121.1);
                } elseif ($firearm->region === '4b') {
                    $lat = $this->getRandomCoordinate(12.8, 13.3);
                    $lng = $this->getRandomCoordinate(120.9, 121.5);
                } elseif ($firearm->region === '5') {
                    $lat = $this->getRandomCoordinate(13.0, 13.7);
                    $lng = $this->getRandomCoordinate(123.2, 123.8);
                }

                $mapData[] = [
                    'lat' => $lat,
                    'lng' => $lng,
                    'type' => 'firearms',
                    'region' => $firearm->region,
                    'title' => $firearm->type,
                    'description' => 'Serial: ' . $firearm->serial_number,
                    'id' => $firearm->id
                ];
            }
        }

        if ($categoryFilter === 'all' || $categoryFilter === 'pags') {
            $pags = PAG::where(function($query) use ($regionCondition) {
                return $regionCondition($query);
            })->get();

            foreach ($pags as $pag) {
                $lat = $this->getRandomCoordinate(13, 14);
                $lng = $this->getRandomCoordinate(120, 123);

                if ($pag->region === '4a') {
                    $lat = $this->getRandomCoordinate(13.9, 14.2);
                    $lng = $this->getRandomCoordinate(120.8, 121.1);
                } elseif ($pag->region === '4b') {
                    $lat = $this->getRandomCoordinate(12.8, 13.3);
                    $lng = $this->getRandomCoordinate(120.9, 121.5);
                } elseif ($pag->region === '5') {
                    $lat = $this->getRandomCoordinate(13.0, 13.7);
                    $lng = $this->getRandomCoordinate(123.2, 123.8);
                }

                $mapData[] = [
                    'lat' => $lat,
                    'lng' => $lng,
                    'type' => 'pags',
                    'region' => $pag->region,
                    'title' => $pag->name,
                    'description' => $pag->affiliation,
                    'id' => $pag->id
                ];
            }
        }

        if ($categoryFilter === 'all' || $categoryFilter === 'surrendered') {
            $surrendered = Surrendered::where(function($query) use ($regionCondition) {
                return $regionCondition($query);
            })->get();

            foreach ($surrendered as $person) {
                $lat = $this->getRandomCoordinate(13, 14);
                $lng = $this->getRandomCoordinate(120, 123);

                if ($person->region === '4a') {
                    $lat = $this->getRandomCoordinate(13.9, 14.2);
                    $lng = $this->getRandomCoordinate(120.8, 121.1);
                } elseif ($person->region === '4b') {
                    $lat = $this->getRandomCoordinate(12.8, 13.3);
                    $lng = $this->getRandomCoordinate(120.9, 121.5);
                } elseif ($person->region === '5') {
                    $lat = $this->getRandomCoordinate(13.0, 13.7);
                    $lng = $this->getRandomCoordinate(123.2, 123.8);
                }

                $mapData[] = [
                    'lat' => $lat,
                    'lng' => $lng,
                    'type' => 'surrendered',
                    'region' => $person->region,
                    'title' => $person->name,
                    'description' => 'Surrendered: ' . $person->date_surrendered,
                    'id' => $person->id
                ];
            }
        }

        return response()->json($mapData);
    }

    /**
     * Helper method to generate random coordinates within a range
     */
    private function getRandomCoordinate($min, $max)
    {
        return $min + (mt_rand() / mt_getrandmax()) * ($max - $min);
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
