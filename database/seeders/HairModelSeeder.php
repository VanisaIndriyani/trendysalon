<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HairModel;

class HairModelSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Oval Layer With Curtain Bangs', 'image' => 'img/model1.png', 'types' => 'Lurus, Ikal, Bergelombang', 'length' => 'Panjang'],
            ['name' => 'Butterfly Hair Cut', 'image' => 'img/model2.png', 'types' => 'Lurus, Ikal, Bergelombang', 'length' => 'Panjang'],
            ['name' => 'Wolf Cut Long Hair', 'image' => 'img/model3.png', 'types' => 'Lurus', 'length' => 'Panjang'],
            ['name' => 'Bob Hair Cut', 'image' => 'img/model2.png', 'types' => 'Lurus', 'length' => 'Pendek'],
            ['name' => 'Pixie Cut', 'image' => 'img/model1.png', 'types' => 'Lurus, Bergelombang', 'length' => 'Panjang'],
            ['name' => 'Face Framing Layers', 'image' => 'img/model3.png', 'types' => 'Lurus', 'length' => 'Panjang'],
            ['name' => 'Wavy Bob', 'image' => 'img/model2.png', 'types' => 'Bergelombang', 'length' => 'Panjang'],
        ];

        foreach ($data as $row) {
            HairModel::updateOrCreate(
                ['name' => $row['name']],
                $row
            );
        }
    }
}