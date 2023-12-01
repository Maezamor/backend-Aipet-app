<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                "name" => "Aipet Clinic",
                "picture" => 'Aipet.jpg',
                "address" => "Denpasar Selatan, Bali",
                "sosial_media_1" => "https://facebbookt/jsd",
                "sosial_media_2" => "Aipet_titok",
                "sosial_media_3" => "Aipet_Ig",
                "description" => "Layanan kesehatan hewan dan anjing",
                "phone" => "0897635473",
                "city" => 'denpasar',
                'lon' => "-7.92849",
                'let' => "8.3333"
            ],
            [
                "name" => "Green Clinic",
                "picture" => 'Green.jpg',
                "address" => "Singosari, Malang",
                "sosial_media_1" => "https://facebbookt/jsd",
                "sosial_media_2" => "Green_titok",
                "sosial_media_3" => "Greem_Ig",
                "description" => "melayani konsutasi dab pemeriksaan kesehatan hewan peliharaan",
                "phone" => "08163483542",
                "city" => "Malang",
                'lon' => "-7.92849",
                'let' => "8.3333"
            ]
        ];

        foreach ($services as $key) {
            Service::create($key);
        }
    }
}
