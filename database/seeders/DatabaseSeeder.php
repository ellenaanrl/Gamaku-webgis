<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // AdminUserSeeder::class,
            UserSeeder::class,
        ]);

        // Create a test admin user
        User::factory()->create([
            'name' => 'ellena',
            'email' => 'ellenanrl@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create some test regular users
        User::factory()->count(3)->create([
            'role' => 'user'
        ]);
    }
}
