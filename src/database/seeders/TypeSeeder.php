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
            ],
            [
                "type" => "Basmut",
            ],
            [
                "type" => "helder",
            ]
        ];

        foreach ($type as $key) {
            Type::create($key);
        }
    }
}
