<?php

namespace Database\Seeders;

use App\Models\Selter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SelterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $selter = [
            [
                "name" => "Rudi Selter",
                "address" => "Denpasar, Bali",
                "description" => "Kami adalah pencita hewan, banyak jenis anjing yang kami rawat disini",
                "phone" => "08124171234",
                "coordinat" => "-7.521570, 112.611938",
            ],
            [
                "name" => "Rayhan Selter",
                "address" => "Gili Manuk, Bali",
                "description" => "Kami adalah pencita hewan, silahkan banyak jenis hewan kami adopsi disini",
                "phone" => "08124171234",
                "coordinat" => "-7.521570, 112.611938",
            ]

        ];
        foreach ($selter as $key) {
            Selter::create($key);
        }
    }
}
