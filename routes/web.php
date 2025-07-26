<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PolylineController;
use App\Http\Controllers\PolygonController;
use App\Http\Controllers\PointController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\JalanController;
use App\Http\Controllers\DrawController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MapadminController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/map', function () {
    return view('map');
})->name('map');

Route::get('/info', [PointController::class, 'showTable'])->name('info');


Route::get('/management', function () {
    return view('management');
})->name('management');


Route::get('/infobangunan', function () {
    return view('infobangunan');
})->name('infobangunan');

Route::get('/infobangunan', [PolygonController::class, 'infobangunan']);
Route::get('/infojalan', [JalanController::class, 'infojalan']);

// Route::get('/login', function () {
//     return view('login');
// })->name('login');

   

    Route::get('/mapadmin', function () {
        return view('admin.map');
    })->name('admin.map')->middleware(['auth']);

Route::get('/info', [PointController::class, 'showTable']);    
// Route::get('/api/info', [InfoController::class, 'getData']); // untuk API fetch JSON

    // ini yg km kira error
    // Route::get('/admin/reports', function () {
    //     // ini butuh data dari database, ini seharusnya diambil dari controller
    //     $reports = \App\Models\DamageReport::orderBy('created_at', 'desc')->get();
    //     return view('admin.reports', compact('reports'));
    // })->name('admin.reports')->middleware(['auth']);


    // Route::get('/admin/reports', function () {
    //     return view('admin.reports');
    // })->middleware(['auth']); // Hapus dulu 'role:admin'


// // Admin routes
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class,])->group(function () {
    Route::get('/report', function () {
        return view('report');
    })->name('report');

});

// Kumpulan routes yang perlu Authentikasi / ['auth']
Route::middleware(['auth'])->group(function () {    //'role:admin' harus dihapus dulu

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard')->middleware(['verified']);

    Route::get('/admin/map', function () {
        return view('admin.map');
    })->name('admin.map')->middleware(['verified']);

    Route::get('/admin/reportmap', function () {
        return view('admin.reportmap');
    })->name('admin.reportmap')->middleware(['verified']);

    Route::get('/admin/reports', [App\Http\Controllers\DamageReportController::class, 'index'])->name('admin.reports');
    Route::patch('/admin/reports/{report}/status', [App\Http\Controllers\DamageReportController::class, 'updateStatus'])->name('admin.reports.update-status');
});

Route::get('/admin/data/points', [PointController::class, 'index']);
Route::get('/admin/data/jalan', [JalanController::class, 'index']); // geojson jalan
Route::get('/admin/data/polygons', [PolygonController::class, 'index']); // geojson bangunan

Route::post('/admin/store-point', [DrawController::class, 'storePoint']);
Route::post('/admin/store-polygon', [DrawController::class, 'storePolygon']);
Route::post('/admin/store-jalan', [MapadminController::class, 'storeJalan']);


Route::post('/admin/update-feature', [MapadminController::class, 'updateFeature']);
Route::post('/admin/delete-feature', [MapadminController::class, 'deleteFeature']);

Route::put('/admin/update-point/{id}', [DrawController::class, 'updatePoint']);

Route::delete('/admin/delete-point/{id}', [DrawController::class, 'deletePoint']);


// Route::post('/admin/update-geometry', [AdminController::class, 'updateGeometry']);
// Route::post('/admin/delete-geometry', [AdminController::class, 'deleteGeometry']);

// // Update routes
// Route::post('/admin/update-point/{id}', [AdminController::class, 'updatePoint']);
// Route::post('/admin/update-polygon/{id}', [AdminController::class, 'updatePolygon']);
// Route::post('/admin/update-jalan/{id}', [AdminController::class, 'updateJalan']);

// // Delete routes
// Route::delete('/admin/delete-point/{id}', [AdminController::class, 'deletePoint']);
// Route::delete('/admin/delete-polygon/{id}', [AdminController::class, 'deletePolygon']);
// Route::delete('/admin/delete-jalan/{id}', [AdminController::class, 'deleteJalan']);

// GeoJSON endpoint for admin damage reports
Route::get('/admin/reports/geojson', [App\Http\Controllers\DamageReportController::class, 'geojson'])->middleware(['verified']);

// Report submission and status routes
Route::post('/submit-report', [App\Http\Controllers\DamageReportController::class, 'store'])->name('report.store');
Route::get('/status-report', [App\Http\Controllers\ReportStatusController::class, 'index'])->name('status.report')->middleware(['auth']);

// API routes for GeoJSON data
Route::resource('polygon', App\Http\Controllers\PolygonController::class);
// Route::resource('point', App\Http\Controllers\PointController::class);
Route::get('/point', [PointController::class, 'index']);

// New route for geojson endpoint for points
Route::get('/point-geojson', [PointController::class, 'geojson']);


Route::get('/polygon', [PolygonController::class, 'index']);


Route::get('/jalan', [JalanController::class, 'index']);

// use Illuminate\Support\Facades\Mail;

// Route::get('/test-email', function () {
//     Mail::raw('Ini tes email dari Laravel', function ($message) {
//         $message->to('Gamakuugm@gmail.com')
//                 ->subject('Tes Email Laravel');
//     });

//     return 'Email terkirim!';
// });


// // // routes/web.php
// // Route::get('/geojson', function () {
// //     return response()->file(storage_path('app/public/geojson/GedungUGM.geojson'));
// // });

require __DIR__.'/auth.php';
