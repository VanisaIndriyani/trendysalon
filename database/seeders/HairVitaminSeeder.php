<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HairOption;

class HairVitaminSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Vitamin A', 'Vitamin B', 'Vitamin C'] as $name) {
            HairOption::updateOrCreate([
                'category' => 'vitamin',
                'name' => $name,
            ], []);
        }
    }
}