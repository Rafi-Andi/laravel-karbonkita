<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Mission;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Pest\Laravel\call;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([UserAdmin::class]);
        // User::factory(10)->create();

        // User::factory(10)->create();

        // // Buat Kategori (5 kategori)
        // Category::factory(5)->create();

        // // Buat Misi (10 misi)
        // Mission::factory(10)->create();

        // // Buat Aktivitas (20 aktivitas)
        // // Karena ActivityFactory sekarang sudah menangani user_id dan mission_id,
        // // kita tidak perlu passing mereka di sini.
        // Activity::factory(20)->create();
    }
}
