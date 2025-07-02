<?php

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class InfoController extends Controller
{
public function index()
{
    return view('info'); // ini hanya tampilkan Blade
}

public function getData()
{
    $data = DB::table('Titik_UGM')->get();
    return response()->json($data);
}
}