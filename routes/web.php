<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CTGController;
use App\Http\Controllers\CTGDocumentController;
use App\Http\Controllers\ISOOperationController;
use App\Http\Controllers\SightingController;
use App\Http\Controllers\FirearmController;
use App\Http\Controllers\PAGController;
use App\Http\Controllers\PAGDocumentController;
use App\Http\Controllers\SurrenderedController;
use App\Http\Controllers\SurrenderedDocumentController;
use App\Http\Controllers\UserController;

// Authentication Routes
Route::get('/', [CustomLoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomLoginController::class, 'login']);
Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout');

// Diagnostic route for debugging - remove in production
Route::get('/debug/surrendered', function() {
    $data = \App\Models\Surrendered::with('documents')->get();
    return response()->json(['success' => true, 'count' => $data->count(), 'data' => $data]);
});

// Direct CTG debug route - no auth required for testing - REMOVE IN PRODUCTION
Route::get('/debug/ctgs', function() {
    $data = \App\Models\CTG::all();
    return response()->json([
        'success' => true,
        'count' => $data->count(),
        'data' => $data,
        'sample_data' => (new App\Http\Controllers\CTGController())->getSampleData()
    ]);
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Chart API Routes
    Route::get('/api/iso-operations-by-month', [DashboardController::class, 'getISOOperationsByMonth'])->name('api.iso-operations-by-month');
    Route::get('/api/regional-distribution', [DashboardController::class, 'getRegionalDistribution'])->name('api.regional-distribution');

    // Map Data API
    Route::get('/api/map-data', [DashboardController::class, 'getMapData'])->name('api.map-data');

    // Admin only routes
    Route::middleware(['checkRole:admin'])->group(function () {
        // Admin specific functionality
        // User Management Routes
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // Data modification routes - apply middleware to restrict user editing
    Route::middleware(['restrict.user.editing'])->group(function () {
        // CTG Routes
        Route::post('/ctgs', [CTGController::class, 'store'])->name('ctgs.store');
        Route::put('/ctgs/{id}', [CTGController::class, 'update'])->name('ctgs.update');
        Route::delete('/ctgs/{id}', [CTGController::class, 'destroy'])->name('ctgs.destroy');

        // CTG Document Routes
        Route::delete('/ctg-documents/{id}', [CTGDocumentController::class, 'destroy'])->name('ctg.documents.destroy');

        // ISO Operations Routes
        Route::post('/iso-operations', [ISOOperationController::class, 'store'])->name('iso-operations.store');
        Route::put('/iso-operations/{id}', [ISOOperationController::class, 'update'])->name('iso-operations.update');
        Route::delete('/iso-operations/{id}', [ISOOperationController::class, 'destroy'])->name('iso-operations.destroy');

        // Sighting Routes
        Route::post('/sightings', [SightingController::class, 'store'])->name('sightings.store');
        Route::put('/sightings/{id}', [SightingController::class, 'update'])->name('sightings.update');
        Route::delete('/sightings/{id}', [SightingController::class, 'destroy'])->name('sightings.destroy');

        // Firearm Routes
        Route::post('/firearms', [FirearmController::class, 'store'])->name('firearms.store');
        Route::put('/firearms/{id}', [FirearmController::class, 'update'])->name('firearms.update');
        Route::delete('/firearms/{id}', [FirearmController::class, 'destroy'])->name('firearms.destroy');

        // PAG Routes
        Route::post('/pags', [PAGController::class, 'store'])->name('pags.store');
        Route::put('/pags/{id}', [PAGController::class, 'update'])->name('pags.update');
        Route::delete('/pags/{id}', [PAGController::class, 'destroy'])->name('pags.destroy');

        // PAG Document Routes
        Route::delete('/pag-documents/{id}', [PAGDocumentController::class, 'destroy'])->name('pag.documents.destroy');

        // Surrendered Routes
        Route::post('/surrendered', [SurrenderedController::class, 'store'])->name('surrendered.store');
        Route::put('/surrendered/{id}', [SurrenderedController::class, 'update'])->name('surrendered.update');
        Route::delete('/surrendered/{id}', [SurrenderedController::class, 'destroy'])->name('surrendered.destroy');

        // Surrendered Document Routes
        Route::delete('/surrendered-documents/{id}', [SurrenderedDocumentController::class, 'destroy'])->name('surrendered.documents.destroy');
    });

    // Read-only routes - accessible to all authenticated users
    Route::get('/ctgs', [CTGController::class, 'index'])->name('ctgs.index');
    Route::get('/ctgs/export', [CTGController::class, 'export'])->name('ctgs.export');
    Route::get('/ctgs/{id}', [CTGController::class, 'show'])->name('ctgs.show');
    Route::post('/ctgs/{ctgId}/documents', [CTGDocumentController::class, 'store'])->name('ctg.documents.store');
    Route::get('/ctg-documents/{id}', [CTGDocumentController::class, 'show'])->name('ctg.documents.show');
    Route::get('/ctg-documents/{id}/download', [CTGDocumentController::class, 'download'])->name('ctg.documents.download');

    Route::get('/iso-operations', [ISOOperationController::class, 'index'])->name('iso-operations.index');
    Route::get('/iso-operations/{id}', [ISOOperationController::class, 'show'])->name('iso-operations.show');

    Route::get('/sightings', [SightingController::class, 'index'])->name('sightings.index');
    Route::get('/sightings/{id}', [SightingController::class, 'show'])->name('sightings.show');

    Route::get('/firearms', [FirearmController::class, 'index'])->name('firearms.index');
    Route::get('/firearms/{id}', [FirearmController::class, 'show'])->name('firearms.show');

    Route::get('/pags', [PAGController::class, 'index'])->name('pags.index');
    Route::get('/pags/{id}', [PAGController::class, 'show'])->name('pags.show');
    Route::post('/pags/{pagId}/documents', [PAGDocumentController::class, 'store'])->name('pag.documents.store');
    Route::get('/pag-documents/{id}', [PAGDocumentController::class, 'show'])->name('pag.documents.show');
    Route::get('/pag-documents/{id}/download', [PAGDocumentController::class, 'download'])->name('pag.documents.download');

    Route::get('/surrendered', [SurrenderedController::class, 'index'])->name('surrendered.index');
    Route::get('/surrendered/export', [SurrenderedController::class, 'export'])->name('surrendered.export');
    Route::get('/surrendered/{id}', [SurrenderedController::class, 'show'])->name('surrendered.show');
    Route::post('/surrendered/{surrenderedId}/documents', [SurrenderedDocumentController::class, 'store'])->name('surrendered.documents.store');
    Route::get('/surrendered-documents/{id}', [SurrenderedDocumentController::class, 'show'])->name('surrendered.documents.show');
    Route::get('/surrendered-documents/{id}/download', [SurrenderedDocumentController::class, 'download'])->name('surrendered.documents.download');

    // Region specific routes
    Route::middleware(['checkRole:RMFB4A'])->group(function () {
        // RMFB4A specific functionality
    });

    // Region 4B specific routes
    Route::middleware(['checkRole:RMFB4B'])->group(function () {
        // RMFB4B specific functionality
    });

    // Region 5 specific routes
    Route::middleware(['checkRole:RMFB5'])->group(function () {
        // RMFB5 specific functionality
    });
});
