<?php

namespace Database\Seeders;

use App\Models\Sterlisation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SterlisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $strilList =  [
            ["name" => "Neutered"],
            ["name" => "Spayed"],
            ["name" => "Vaccinated"],
            ["name" => "Not Sterillized"],
        ];

        foreach ($strilList as $key) {
            Sterlisation::create($key);
        }
    }
}
