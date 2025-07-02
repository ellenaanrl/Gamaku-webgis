<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Point;
use App\Models\Polygon;
use Illuminate\Support\Facades\DB;

class DrawController extends Controller
{
    public function storePoint(Request $request)
    {
        $geojson = $request->input('geometry');
        $properties = $request->input('properties', []);

        DB::table('Titik_UGM')->insert([
            'geom' => DB::raw("ST_SetSRID(ST_GeomFromGeoJSON('" . json_encode($geojson) . "'), 4326)"),
            'Nama' => $properties['Nama'] ?? 'Titik Baru',
            'Unit' => $properties['Unit'] ?? '-',
            'Jenis_Bang' => $properties['Jenis_Bang'] ?? '-',
        ]);

        return response()->json(['message' => 'Point saved']);
    }

    public function storePolygon(Request $request)
{
    try {
        $geojson = $request->input('geometry');
        $nama = $request->input('nama') ?? 'Bangunan Baru';

        if (!$geojson || !is_string($geojson)) {
            return response()->json(['message' => 'Invalid GeoJSON'], 400);
        }

        DB::table('Bangunan_UGM')->insert([
            'nama' => $nama,
            'geom' => DB::raw("ST_Transform(ST_Multi(ST_SetSRID(ST_GeomFromGeoJSON('{$geojson}'), 4326)), 32749)"),
            'shape_area' => 0,
            'shape_leng' => 0,
        ]);

        return response()->json(['message' => 'Polygon saved successfully']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error saving polygon: ' . $e->getMessage()], 500);
    }
}


}
