<?php

namespace App\Http\Controllers;

use App\Models\DamageReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Mail\NewReportNotification;
use Illuminate\Support\Facades\Mail;

class DamageReportController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Received damage report submission', $request->all());

        $validated = $request->validate([
            'reporter_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'category' => 'required|string|max:255',
            'subcategory' => 'required|string|max:255',
            'floor'=> 'required|string|max:255',
            'description' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'required|string|max:255',
            'photo' => 'required|image|max:2048', // max 2MB
        ]);

        try {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                Log::info('Processing photo upload');
                $photoPath = $request->file('photo')->store('damage-photos', 'public');
                Log::info('Photo stored at: ' . $photoPath);
            } else {
                Log::warning('No photo file received');
            }

            $report = DamageReport::create([
                'reporter_name' => $request->reporter_name,
                'position' => $request->position,
                'department' => $request->department,
                'phone' => $request->phone,
                'email' => $request->email,
                'user_id' => Auth::id(),
                'category' => $request->category,
                'subcategory' => $request->subcategory,
                'floor' => $request->floor,
                'description' => $request->description,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'lokasi' => $request->lokasi,
                'photo_path' => $photoPath,
                'status' => 'pending'
            ]);

            Log::info('Damage report created successfully', ['report_id' => $report->id]);

            
            try {
                Mail::to('gamakuugm@gmail.com')->send(new NewReportNotification($report));
                Log::info('Email notification sent to admin');
            } catch (\Exception $e) {
                Log::error('Failed to send email: ' . $e->getMessage());
            }

  } catch (\Exception $e) {
    Log::error('Failed to create damage report: ' . $e->getMessage());
    return redirect()->back()
        ->withInput()
        ->with('error', 'Failed to submit report: ' . $e->getMessage()); // DEBUG
}

        // Redirect to status_report page after successful submission
        return redirect()->route('status.report')->with('report_submitted', true);
    }

    public function index()
    {
        $reports = DamageReport::orderBy('created_at', 'desc')->get();
        return view('admin.reports', compact('reports'));
    }

    public function updateStatus(Request $request, DamageReport $report)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,rejected'
        ]);

        $report->update(['status' => $request->status]);
        return redirect()->back()->with('status_updated', 'Report status has been updated.');
    }

    public function geojson()
    {
        $reports = DamageReport::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $features = $reports->map(function ($report) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        (float) $report->longitude,
                        (float) $report->latitude
                    ]
                ],
                'properties' => [
                    'id' => $report->id,
                    'reporter_name' => $report->reporter_name,
                    'category' => $report->category,
                    'subcategory' => $report->subcategory,
                    'floor' => $report->floor,
                    'description' => $report->description,
                    'lokasi' => $report->lokasi,
                    'status' => $report->status,
                    'created_at' => $report->created_at,
                    'photo_path' => $report->photo_path ? Storage::url($report->photo_path) : null,
                ]
            ];
        });

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];

        return response()->json($geojson);
    }
}
