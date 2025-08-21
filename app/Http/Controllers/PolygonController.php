<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Polygon;
use Illuminate\Support\Facades\DB;

class PolygonController extends Controller
{
    public function index()
{
    $data = DB::table('Bangunan_UGM4')
        ->select('nama', 'jml_lantai', 'kategori', 'foto', DB::raw('ST_AsGeoJSON(ST_Transform(geom, 4326)) as geom'))
        ->get();

    $features = $data->map(function ($item) {
        return [
            'type' => 'Feature',
            'geometry' => json_decode($item->geom),
            'properties' => [
                'nama' => $item->nama,
                'jml_lantai' => $item->jml_lantai ?? null, 
                'kategori' => $item->kategori ?? null,
                'foto' => $item->foto ? asset($item->foto) : null,
            ]
        ];
    });

    return response()->json([
        'type' => 'FeatureCollection',
        'features' => $features
    ]);
}

public function infobangunan()
    {
        $buildings = Polygon::all(); 
        return view('infobangunan', compact('buildings'));
    }


}


    

