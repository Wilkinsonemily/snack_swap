<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password123'),
            'is_admin' => 1, 
        ]);

        $this->call([
            CategorySeeder::class,
            FoodSeeder::class,
            SwapSeeder::class,
        ]);
    }
}