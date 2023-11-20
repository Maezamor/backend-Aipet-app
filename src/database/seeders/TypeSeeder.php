<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $type = [
            [
                "type" => "Buldock",
                "kelompok" => "anjing lucu",
                "group" => "working",

            ],
            [
                "type" => "Basmut",
                "kelompok" =>  "Anjing Besar",
                "group" => "herding",

            ],
            [
                "type" => "helder",
                "kelompok" => "anjing kecil",
                "group" => "toy",
            ]
        ];

        foreach ($type as $key) {
            Type::create($key);
        }
    }
}
