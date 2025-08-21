<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Polygon extends Model
{
    protected $table = 'Bangunan_UGM4'; // Pastikan ini sesuai nama tabel di PostgreSQL

    protected $primaryKey = 'id'; // Sesuaikan dengan primary key-mu
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'jml_lantai',
        'kategori',
        'shape_area',
        'shape_leng',
        'geom',
    ];
}


