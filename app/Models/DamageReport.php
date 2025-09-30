<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_name',
        'position',
        'department',
        'phone',
        'email',
        'category',
        'subcategory',
        'impact',
        'floor',
        'description',
        'latitude',
        'longitude',
        'lokasi',
        'photo_path',
        'status',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
