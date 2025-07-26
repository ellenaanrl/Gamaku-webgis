<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DamageReport;
use Illuminate\Support\Facades\Auth;

class ReportStatusController extends Controller
{    public function index()
    {
        $reports = DamageReport::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('status-report', compact('reports'));
    }
}