<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    // Your table name (exactly as shown in PostgreSQL)
    protected $table = 'Titik_UGM';
    
    // Primary key
    protected $primaryKey = 'id';
    
    // Disable timestamps since your table doesn't have created_at/updated_at
    public $timestamps = false;
    
    // Fillable columns
    protected $fillable = [
        'Nama',
        'Unit', 
        'Jenis_Bang',
        'Dokumentasi'
    ];
    
    // Note: We're excluding 'geom' from fillable since it's geometry data
    // and needs special handling
}