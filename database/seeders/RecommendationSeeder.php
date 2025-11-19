<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recommendation;

class RecommendationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records = [
            [
                'name' => 'Rina',
                'phone' => '08211402345',
                'hair_length' => 'Panjang',
                'hair_type' => 'Lurus',
                'hair_condition' => 'Sehat',
                'face_shape' => 'Oval',
                'recommended_models' => 'Model A, Model B',
            ],
            [
                'name' => 'Sari',
                'phone' => '08221435947',
                'hair_length' => 'Sedang',
                'hair_type' => 'Keriting',
                'hair_condition' => 'Sehat',
                'face_shape' => 'Kotak',
                'recommended_models' => 'Model B, Model C',
            ],
            [
                'name' => 'Valery',
                'phone' => '08211435949',
                'hair_length' => 'Panjang',
                'hair_type' => 'Bergelombang',
                'hair_condition' => 'Rusak',
                'face_shape' => 'Bulat',
                'recommended_models' => 'Model C, Model D',
            ],
            [
                'name' => 'Axel',
                'phone' => '08211402357',
                'hair_length' => 'Pendek',
                'hair_type' => 'Keriting',
                'hair_condition' => 'Kering',
                'face_shape' => 'Kotak',
                'recommended_models' => 'Model A, Model D',
            ],
            [
                'name' => 'Galinda',
                'phone' => '08211402359',
                'hair_length' => 'Sedang',
                'hair_type' => 'Bergelombang',
                'hair_condition' => 'Kering',
                'face_shape' => 'Oval',
                'recommended_models' => 'Model A, Model C',
            ],
        ];

        foreach ($records as $data) {
            Recommendation::create($data);
        }
    }
}
