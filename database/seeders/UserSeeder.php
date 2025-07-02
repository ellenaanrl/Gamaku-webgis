<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         User::create([
            'name' => 'User',
            'email' => 'user@ugm.ac.id',
            'password' => Hash::make('user1234'), // password yang dienkripsi biasanya 8 karakter atau lebih
            'role' => 'user', // pastikan kolom 'role' sudah ditambahkan ke tabel users
        ]);
    }
}
