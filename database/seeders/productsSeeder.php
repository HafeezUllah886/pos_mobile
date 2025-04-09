<?php

namespace Database\Seeders;

use App\Models\products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class productsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => "Samsung S22 Ultra 8/128", "pprice" => 350000, "price" => 380000, 'discount' => 15000, 'catID' => 1],
            ['name' => "Vivo X200 Pro 16/512", "pprice" => 320000, "price" => 340000, 'discount' => 10000, 'catID' => 7],
            ['name' => "Iphone 16 Pro Max 1TB", "pprice" => 700000, "price" => 720000, 'discount' => 5000, 'catID' => 2],
        ];
        products::insert($data);
    }
}
