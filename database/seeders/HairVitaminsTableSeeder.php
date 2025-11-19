<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HairVitamin;

class HairVitaminsTableSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Vitamin A', 'hair_type' => 'Sehat'],
            ['name' => 'Vitamin B', 'hair_type' => 'Kering'],
            ['name' => 'Vitamin C', 'hair_type' => 'Rusak'],
        ];

        foreach ($items as $it) {
            HairVitamin::updateOrCreate([
                'name' => $it['name'],
            ], [
                'hair_type' => $it['hair_type'],
            ]);
        }
    }
}