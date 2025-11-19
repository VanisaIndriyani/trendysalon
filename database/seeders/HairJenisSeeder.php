<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HairOption;

class HairJenisSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Lurus', 'Ikal', 'Bergelombang', 'Keriting'] as $name) {
            HairOption::updateOrCreate([
                'category' => 'jenis',
                'name' => $name,
            ], []);
        }
    }
}