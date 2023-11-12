<?php

namespace Database\Seeders;

use App\Models\gender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gender = [
            [
                "gender" => "Male",
            ],
            [
                "gender" => "Female",
            ]
        ];

        foreach ($gender as $key) {
            gender::create($key);
        }
    }
}
