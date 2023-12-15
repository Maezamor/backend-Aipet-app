<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'fieren',
            'password' => Hash::make('fieren123'),
            'name' => 'fieren si penyihir',
            'token' => 'fierencoll',
            'address' => 'antasia kingdom',
            'phone' => '123456',
            'email' => 'fieren@gmail.com',
            'role_id' => 3,
        ]);
    }
}
