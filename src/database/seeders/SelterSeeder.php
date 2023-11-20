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
                "picture" => "Rudi.jpg",
                "address" => "Denpasar, Bali",
                "sosial_media_1" => "facebook",
                "sosial_media_2" => "instagram",
                "sosial_media_3" => 'aipet.id',
                "description" => "Kami adalah pencita hewan, banyak jenis anjing yang kami rawat disini",
                "phone" => "08124171234",
                "city" => "Denpasar",
                "lon" => "-7.521570",
                "let" => "112.557248",
            ],
            [
                "name" => "Rayhan Selter",
                "picture" => "Rayhan.jpg",
                "address" => "Gili Manuk, Bali",
                "sosial_media_1" => "facebook",
                "sosial_media_2" => "instagram",
                "sosial_media_3" => 'aipet.id',
                "description" => "Kami adalah pencita hewan, silahkan banyak jenis hewan kami adopsi disini",
                "phone" => "08124171234",
                "city" => "Denpasar",
                "lon" => "-7.521570",
                "let" => "112.557248",
            ]

        ];
        foreach ($selter as $key) {
            Selter::create($key);
        }
    }
}
