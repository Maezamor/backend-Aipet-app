<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = [
            [
                "role" => "admin",
            ],
            [
                "role" => "selter",
            ],
            [
                "role" => "members"
            ]
        ];

        foreach ($role as $key) {
            Role::create($key);
        }
    }
}
