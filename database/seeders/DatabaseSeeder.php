<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default admin user and domain data
        $this->call([
            SuperAdminSeeder::class,
            AdminUserSeeder::class,
            HairOptionsSeeder::class, // length/type/condition
            HairModelSeeder::class,   // data model rambut
            HairJenisSeeder::class,   // pilihan jenis (kategori 'jenis')
            HairVitaminSeeder::class, // pilihan vitamin (kategori 'vitamin')
            HairVitaminsTableSeeder::class, // data tabel vitamins untuk CRUD
            RecommendationSeeder::class, // data rekomendasi contoh
        ]);
    }
}
