<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin WebGIS',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // password yang dienkripsi
            'role' => 'admin', // pastikan kolom 'role' sudah ditambahkan ke tabel users
        ]);

        User::create([
        'name' => 'Admin 2',
        'email' => 'Gamakuugm@gmail.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);

    }
}
