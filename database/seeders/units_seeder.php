<?php

namespace Database\Seeders;

use App\Models\units;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class units_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => "Nos", "value" => 1],
            ['name' => "Box of 10's", "value" => 10],
        ];
        units::insert($data);
    }
}
