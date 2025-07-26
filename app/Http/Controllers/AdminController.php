<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Point;
use App\Models\Jalan;
use App\Models\Polygon;

class AdminController extends Controller
{
    public function updateGeometry(Request $request)
    {
        $type = $request->type;
        $id = $request->id;
        $geometry = json_encode($request->geometry);

        if ($type == 'Point') {
            $model = Point::find($id);
        } elseif ($type == 'Polygon') {
            $model = Polygon::find($id);
        } elseif ($type == 'LineString') {
            $model = Jalan::find($id);
        } else {
            return response()->json(['message' => 'Tipe geometris tidak dikenali'], 400);
        }

        if (!$model) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $model->geom = DB::raw("ST_SetSRID(ST_GeomFromGeoJSON('$geometry'), 4326)");
        $model->save();

        return response()->json(['message' => 'Data berhasil diubah']);
    }

    public function deleteGeometry(Request $request)
    {
        $type = $request->type;
        $id = $request->id;

        if ($type == 'Point') {
            $model = Point::find($id);
        } elseif ($type == 'Polygon') {
            $model = Polygon::find($id);
        } elseif ($type == 'LineString') {
            $model = Jalan::find($id);
        } else {
            return response()->json(['message' => 'Tipe geometris tidak dikenali'], 400);
        }

        if (!$model) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $model->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}