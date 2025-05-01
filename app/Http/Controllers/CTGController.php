<?php

namespace App\Http\Controllers;

use App\Models\CTG;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CTGController extends Controller
{
    /**
     * Display a listing of CTGs.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = CTG::query();

            // Apply region filter if provided
            if ($request->has('region') && $request->region !== 'all') {
                $query->where('region', $request->region);
            }

            $ctgs = $query->get();

            return response()->json([
                'success' => true,
                'data' => $ctgs
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching CTGs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching CTG data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created CTG.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'region' => 'required|string|max:10',
                'address' => 'nullable|string',
                'pob' => 'nullable|string',
                'dob' => 'nullable|date',
                'affiliated_front' => 'nullable|string',
                'last_seen' => 'nullable|date',
                'status' => 'required|string|in:active,neutralized,surrendered,deceased',
                'photo' => 'nullable|image|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoPath = $photo->store('ctg_photos', 'public');
                $data['photo_path'] = $photoPath;
            }

            $ctg = CTG::create($data);

            return response()->json([
                'success' => true,
                'message' => 'CTG member added successfully',
                'data' => $ctg
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error creating CTG: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating CTG: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified CTG.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $ctg = CTG::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $ctg
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'CTG record not found'
            ], 404);
        }
    }

    /**
     * Update the specified CTG.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $ctg = CTG::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'region' => 'required|string|max:10',
                'address' => 'nullable|string',
                'pob' => 'nullable|string',
                'dob' => 'nullable|date',
                'affiliated_front' => 'nullable|string',
                'last_seen' => 'nullable|date',
                'status' => 'required|string|in:active,neutralized,surrendered,deceased',
                'photo' => 'nullable|image|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($ctg->photo_path) {
                    Storage::disk('public')->delete($ctg->photo_path);
                }

                $photo = $request->file('photo');
                $photoPath = $photo->store('ctg_photos', 'public');
                $data['photo_path'] = $photoPath;
            }

            $ctg->update($data);

            return response()->json([
                'success' => true,
                'message' => 'CTG record updated successfully',
                'data' => $ctg
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating CTG: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating CTG: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified CTG.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $ctg = CTG::findOrFail($id);

            // Delete photo if exists
            if ($ctg->photo_path) {
                Storage::disk('public')->delete($ctg->photo_path);
            }

            $ctg->delete();

            return response()->json([
                'success' => true,
                'message' => 'CTG record deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting CTG: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting CTG: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export CTG data to CSV
     *
     * @param Request $request
     * @return StreamedResponse
     */
    public function export(Request $request)
    {
        try {
            $query = CTG::query();

            // Apply region filter if provided
            if ($request->has('region') && $request->region !== 'all') {
                $query->where('region', $request->region);
            }

            $ctgs = $query->get();

            $filename = 'ctg_records_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function() use ($ctgs) {
                $file = fopen('php://output', 'w');

                // Add CSV headers
                fputcsv($file, [
                    'ID',
                    'Name',
                    'Region',
                    'Address',
                    'Place of Birth',
                    'Date of Birth',
                    'Affiliated Front',
                    'Last Seen',
                    'Status',
                ]);

                // Add data rows
                foreach ($ctgs as $ctg) {
                    fputcsv($file, [
                        $ctg->id,
                        $ctg->name,
                        $ctg->region,
                        $ctg->address,
                        $ctg->pob,
                        $ctg->dob ? date('Y-m-d', strtotime($ctg->dob)) : '',
                        $ctg->affiliated_front,
                        $ctg->last_seen ? date('Y-m-d H:i:s', strtotime($ctg->last_seen)) : '',
                        $ctg->status,
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            \Log::error('Error exporting CTG data: ' . $e->getMessage());
            return back()->with('error', 'Failed to export CTG data: ' . $e->getMessage());
        }
    }

    /**
     * Get sample data for fallback.
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
}