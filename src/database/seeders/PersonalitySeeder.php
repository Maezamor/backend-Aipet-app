<?php

namespace Database\Seeders;

use App\Models\Personality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $question = [
            [
                "code_ques" => sprintf("%05s", mt_rand(1, 99999)),
                "question" => "Amet nulla voluptate sunt ullamco consequat pariatur nulla adipisicing eiusmod anim minim pariatur eu ?"
            ],
            [
                "code_ques" => sprintf("%05s", mt_rand(1, 99999)),
                "question" => "Ex non ut nostrud eu id nulla nostrud magna qui?"
            ],
            [
                "code_ques" => sprintf("%05s", mt_rand(1, 99999)),
                "question" => "Ea sint culpa in tempor?"
            ],
        ];
        foreach ($question as $key) {
            Personality::create($key);
        }
    }
}
