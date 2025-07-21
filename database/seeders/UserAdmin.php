<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserAdmin extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name" => "Rafi Andi",
            "email" => "rafi@admin.com",
            "email_verified_at" => now(),
            "password" => bcrypt('password'),
            'provinsi' => fake()->text(rand(5, 7)),
            'kabupaten' => fake()->text(rand(5, 7)),
            'kecamatan' => fake()->text(rand(5, 7)),
            'kelurahan' => fake()->text(rand(5, 7)),
            'rw' => fake()->randomNumber(),
            'rt' => fake()->randomNumber(),
            'role' => 'admin',
            'gender' => 'pria',
        ]);
    }
}
