<?php

namespace Database\Seeders;

use App\Models\HairOption;
use Illuminate\Database\Seeder;

class HairOptionsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'length' => ['Pendek', 'Panjang'],
            'type' => ['Lurus', 'Ikal', 'Bergelombang', 'Keriting'],
            'condition' => ['Rusak', 'Kering', 'Sehat'],
        ];

        foreach ($data as $category => $names) {
            foreach ($names as $name) {
                HairOption::firstOrCreate([
                    'category' => $category,
                    'name' => $name,
                ]);
            }
        }
    }
}