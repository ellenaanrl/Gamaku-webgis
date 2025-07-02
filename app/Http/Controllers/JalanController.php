<?php

namespace App\Http\Controllers;
use App\Models\Jalan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class JalanController extends Controller
{
    public function index()
    {
        $data = DB::table('Jalan_UGM')
            ->select('nama', DB::raw('ST_AsGeoJSON(ST_Transform(geom, 4326)) as geojson'))
            ->get();

        $features = $data->map(function ($row) {
            return [
                'type' => 'Feature',
                'geometry' => json_decode($row->geojson),
                'properties' => [
                    'nama' => $row->nama,
                ]
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    public function infojalan(Request $request)
{
    $search = $request->query('search');

    $roads = Jalan::when($search, function ($query, $search) {
        return $query->where('nama', 'ILIKE', "%{$search}%"); // ILIKE untuk PostgreSQL
    })->orderBy('nama')
      ->paginate(10)
      ->appends(['search' => $search]);

    return view('infojalan', compact('roads', 'search'));
}

}
