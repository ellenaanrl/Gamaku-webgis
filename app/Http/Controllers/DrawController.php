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
            'nama' => $properties['nama'] ?? 'Titik Baru',
            'unit' => $properties['unit'] ?? '-',
            'jenis_bang' => $properties['jenis_bang'] ?? '-',
        ]);

        return response()->json(['message' => 'Titik Bangunan berhasil disimpan']);
    }

    public function storePolygon(Request $request)
    {
        try {
            $geometry = $request->input('geometry');
            $properties = $request->input('properties', []);
            $nama = $properties['nama'] ?? 'Bangunan Baru';

            if (!$geometry || !is_array($geometry)) {
                return response()->json(['message' => 'Invalid GeoJSON geometry'], 400);
            }

            $geojson = json_encode($geometry);

            DB::table('Bangunan_UGM4')->insert([
                'nama' => $nama,
                'jml_lantai' => $properties['jml_lantai'] ?? 1,
                'geom' => DB::raw("ST_Transform(ST_Multi(ST_SetSRID(ST_GeomFromGeoJSON('{$geojson}'), 4326)), 32749)"),
                'shape_area' => 0,
                'shape_leng' => 0,
            ]);

            return response()->json(['message' => 'Polygon berhasil disimpan']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error saving polygon: ' . $e->getMessage()], 500);
        }
    }

    public function updatePoint(Request $request, $id)
{
    try {
        $geojson = $request->input('geometry');
        $properties = $request->input('properties', []);

        DB::table('Titik_UGM')->where('id', $id)->update([
            'geom' => DB::raw("ST_SetSRID(ST_GeomFromGeoJSON('" . json_encode($geojson) . "'), 4326)"),
            'nama' => $properties['nama'] ?? 'Titik Baru',
            'unit' => $properties['unit'] ?? '-',
            'jenis_bang' => $properties['jenis_bang'] ?? '-',
        ]);

        return response()->json(['message' => 'Titik berhasil diperbarui']);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Gagal memperbarui titik',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function deletePoint($id)
{
    try {
        DB::table('Titik_UGM')->where('id', $id)->delete();

        return response()->json(['message' => 'Titik berhasil dihapus']);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Gagal menghapus titik',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
