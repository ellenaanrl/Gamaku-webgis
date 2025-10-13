<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Point;

class PointController extends Controller
{
    
    public function index()
    {
        $points = Point::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) as geom_json")->get();

        $features = [];

        foreach ($points as $point) {

            if (!isset($point->geom_json)) {
                continue;
            }

            $geojson = json_decode($point->geom_json, true);
            if (!$geojson || !isset($geojson['coordinates'])) {
                continue;
            }

            $features[] = [
                'type' => 'Feature',
                'id' => $point->id,
                'geometry' => $geojson,
                'properties' => [
                    'nama' => $point->nama ?? '',
                    'jenis_bang' => $point->jenis_bang ?? '',
                    'unit' => $point->unit ?? '',
                    'dokumentasi' => asset($point->dokumentasi),
                ],
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    public function showTable()
    {
        $buildings = Point::all();
        return view('info', ['buildings' => $buildings]);
    }
}
