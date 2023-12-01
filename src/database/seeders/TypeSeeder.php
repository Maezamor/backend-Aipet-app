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
                "type" => "indonesia street dog",
                "size" => "Medium",
                "activity_level" => "Moderate",
                "groups" => "non-sporting Group",

            ],
            [
                "type" => "golden retriever",
                "size" => "large",
                "activity_level" => "high",
                "groups" => "working group",

            ],
            [
                "type" => "Poodie",
                "size" => "small",
                "activity_level" => "moderate",
                "groups" => "toy group",
            ]
        ];

        foreach ($type as $key) {
            Type::create($key);
        }
    }
}
