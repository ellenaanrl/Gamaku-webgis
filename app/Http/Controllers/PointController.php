<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Point;

class PointController extends Controller
{
    /**
     * Mengembalikan data GeoJSON untuk titik dari tabel Titik_UGM
     */
    public function index()
    {
        // Ambil data dan konversi geometry ke GeoJSON
        $points = Point::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) as geom_json")->get();

        $features = [];

        foreach ($points as $point) {
            // Pastikan GeoJSON tersedia
            if (!isset($point->geom_json)) {
                continue;
            }

            $geojson = json_decode($point->geom_json, true);
            if (!$geojson || !isset($geojson['coordinates'])) {
                continue;
            }

            // Tambahkan ke daftar fitur
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

        // Return sebagai GeoJSON FeatureCollection
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    /**
     * Menampilkan tabel informasi titik (halaman info)
     */
    public function showTable()
    {
        $buildings = Point::all();
        return view('info', ['buildings' => $buildings]);
    }
}
