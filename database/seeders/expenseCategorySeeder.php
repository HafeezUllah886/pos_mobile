<?php

namespace Database\Seeders;

use App\Models\expenseCategories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class expenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cats = [
            ['name' => 'Category 1'],
            ['name' => 'Category 2'],
        ];

        expenseCategories::insert($cats);
    }
}
