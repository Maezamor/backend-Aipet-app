<?php

namespace Database\Seeders;

use App\Models\Dog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dog = [
            [
                "name" => "Konyako",
                "picture" => "https://tse4.mm.bing.net/th?id=OIP.3aUFQJEofq-NJYkdGHwvWwHaE7&pid=Api&P=0&h=180",
                "age" => "4",
                "rescue_story" => "Konyako adalah anjing yang penurut namun karena tuanya sudah meninggal dia tampak murung dan tidak bersemnagat lagi",
                "character" => "pendiam, penurut",
                "gender" => "Male",
                "type_id" => 1,
                "steril_id" => 1,
                "selter_id" => 1,
            ],
            [
                "name" => "Kotsuke",
                "picture" => "https://live.staticflickr.com/8024/6981571998_bc6de0453e.jpg",
                "age" => "5",
                "rescue_story" => "Kotsuke adalah anjing yang garang karena dulunya perna dipukuli oleh pemilik dan warga sekitar, kami menemukanya terluka dipinggir jalan",
                "character" => "tegas, garang",
                "gender" => "Female",
                "type_id" => 2,
                "steril_id" => 2,
                "selter_id" => 1,
            ]
        ];

        foreach ($dog as $key) {
            Dog::create($key);
        }
    }
}
