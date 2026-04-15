<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitPcs = Unit::where('abbreviation', 'шт')->first();
        $unitKg = Unit::where('abbreviation', 'кг')->first();
        $unitPack = Unit::where('abbreviation', 'уп')->first();

        $categoryElectronics = Category::where('name', 'Электроника')->first();
        $categoryOffice = Category::where('name', 'Офисные принадлежности')->first();
        $categoryFood = Category::where('name', 'Продукты питания')->first();

        $products = [
            [
                'sku' => 'PROD-001',
                'name' => 'Ноутбук Lenovo ThinkPad',
                'description' => 'Бизнес-ноутбук 15.6"',
                'category_id' => $categoryElectronics->id,
                'unit_id' => $unitPcs->id,
                'min_stock' => 5,
                'stock_quantity' => 12,
            ],
            [
                'sku' => 'PROD-002',
                'name' => 'Бумага А4',
                'description' => 'Офисная бумага, 500 листов',
                'category_id' => $categoryOffice->id,
                'unit_id' => $unitPack->id,
                'min_stock' => 20,
                'stock_quantity' => 50,
            ],
            [
                'sku' => 'PROD-003',
                'name' => 'Кофе растворимый',
                'description' => 'Кофе премиум класса, 100г',
                'category_id' => $categoryFood->id,
                'unit_id' => $unitPcs->id,
                'min_stock' => 10,
                'stock_quantity' => 25,
            ],
            [
                'sku' => 'PROD-004',
                'name' => 'Монитор 24"',
                'description' => 'IPS монитор Full HD',
                'category_id' => $categoryElectronics->id,
                'unit_id' => $unitPcs->id,
                'min_stock' => 3,
                'stock_quantity' => 8,
            ],
            [
                'sku' => 'PROD-005',
                'name' => 'Сахар-песок',
                'description' => 'Сахар белый, 1кг',
                'category_id' => $categoryFood->id,
                'unit_id' => $unitKg->id,
                'min_stock' => 30,
                'stock_quantity' => 100,
            ],
            [
                'sku' => 'PROD-006',
                'name' => 'Клавиатура USB',
                'description' => 'Проводная клавиатура',
                'category_id' => $categoryElectronics->id,
                'unit_id' => $unitPcs->id,
                'min_stock' => 10,
                'stock_quantity' => 0,
            ],
            [
                'sku' => 'PROD-007',
                'name' => 'Ручка шариковая',
                'description' => 'Синяя, 10 шт в упаковке',
                'category_id' => $categoryOffice->id,
                'unit_id' => $unitPack->id,
                'min_stock' => 15,
                'stock_quantity' => 5,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
