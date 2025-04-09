<?php

namespace Database\Seeders;

use App\Models\categories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class categorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cats = [
            ['name' => 'Samsung'],
            ['name' => 'Apple'],
            ['name' => 'Oppo'],
            ['name' => 'Xiaomi'],
            ['name' => 'OnePlus'],
            ['name' => 'Realme'],
            ['name' => 'Vivo'],
            ['name' => 'Oppo'],
            ['name' => 'Nokia'],
            ['name' => 'LG'],
            ['name' => 'Sony'],
            ['name' => 'HTC'],
            ['name' => 'Google'],
        ];

        categories::insert($cats);
    }
}
