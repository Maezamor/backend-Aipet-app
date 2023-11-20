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
                "address" => "Denpasar Selatan, Bali",
                "sosial_media_1" => "https://facebbookt/jsd",
                "description" => "Layanan kesehatan hewan dan anjing",
                "phone" => "0897635473",
                "city" => 'denpasar',
            ],
            [
                "name" => "Green Clinic",
                "address" => "Singosari, Malang",
                "sosial_media_1" => "https://facebbookt/jsd",
                "description" => "melayani konsutasi dab pemeriksaan kesehatan hewan peliharaan",
                "phone" => "08163483542",
                "city" => "Malang",
            ]
        ];

        foreach ($services as $key) {
            Service::create($key);
        }
    }
}
