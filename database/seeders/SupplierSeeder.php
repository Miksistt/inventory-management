<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'ООО ТехноТрейд',
                'contact_person' => 'Иванов Иван Иванович',
                'phone' => '+7 (999) 123-45-67',
                'email' => 'info@technotrade.ru',
                'address' => 'г. Москва, ул. Техническая, д. 15',
            ],
            [
                'name' => 'ИП Петров А.А.',
                'contact_person' => 'Петров Алексей Александрович',
                'phone' => '+7 (999) 765-43-21',
                'email' => 'petrov@mail.ru',
                'address' => 'г. Санкт-Петербург, пр. Невский, д. 100',
            ],
            [
                'name' => 'ЗАО КанцМаркет',
                'contact_person' => 'Сидорова Елена Викторовна',
                'phone' => '+7 (999) 555-88-99',
                'email' => 'sales@kancmarket.ru',
                'address' => 'г. Екатеринбург, ул. Офисная, д. 7',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
