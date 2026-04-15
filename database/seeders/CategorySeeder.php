<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Электроника', 'description' => 'Электронные устройства и компоненты'],
            ['name' => 'Офисные принадлежности', 'description' => 'Канцелярские товары'],
            ['name' => 'Бытовая химия', 'description' => 'Чистящие и моющие средства'],
            ['name' => 'Продукты питания', 'description' => 'Продукты длительного хранения'],
            ['name' => 'Строительные материалы', 'description' => 'Материалы для ремонта'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
