<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\expenseCategories;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       $this->call(categorySeeder::class);
       $this->call(userSeeder::class);
       $this->call(units_seeder::class);
       $this->call(productsSeeder::class);
       $this->call(accountSeeder::class);
       $this->call(warehouseSeeder::class);
       $this->call(expenseCategorySeeder::class);
    }
}
