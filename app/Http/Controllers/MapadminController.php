<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapadminController extends Controller
{

public function updateFeature(Request $request)
{
    $geom = json_encode($request->input('geometry'));
    $properties = $request->input('properties');
    $nama = $properties['nama'] ?? $properties['Nama'] ?? null;

    if (!$nama) return response()->json(['error' => 'Nama tidak ditemukan.'], 400);

    // Update berdasarkan nama
    DB::table('Bangunan_UGM2')
        ->where('nama', $nama)
        ->update([
            'geom' => DB::raw("ST_SetSRID(ST_GeomFromGeoJSON('$geom'), 4326)")
        ]);

    return response()->json(['message' => 'Update berhasil.']);
}

public function deleteFeature(Request $request)
{
    $geometry = $request->input('geometry');
    $properties = $request->input('properties');
    $nama = $properties['nama'] ?? $properties['Nama'] ?? null;

    if (!$nama || !$geometry['type']) {
        return response()->json(['error' => 'Data tidak lengkap.'], 400);
    }

    if ($geometry['type'] === 'Point') {
        DB::table('Titik_UGM')->where('Nama', $nama)->delete();
    } elseif ($geometry['type'] === 'Polygon') {
        DB::table('Bangunan_UGM2')->where('nama', $nama)->delete();
    } else {
        return response()->json(['error' => 'Tipe geometri tidak didukung.'], 400);
    }

    return response()->json(['message' => 'Hapus berhasil.']);
}

}