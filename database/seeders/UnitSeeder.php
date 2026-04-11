<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Штука', 'abbreviation' => 'шт'],
            ['name' => 'Килограмм', 'abbreviation' => 'кг'],
            ['name' => 'Литр', 'abbreviation' => 'л'],
            ['name' => 'Метр', 'abbreviation' => 'м'],
            ['name' => 'Упаковка', 'abbreviation' => 'уп'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
