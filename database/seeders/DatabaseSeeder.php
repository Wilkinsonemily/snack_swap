<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
            FoodSeeder::class,
            SnackSeeder::class,
            SwapSeeder::class,
        ]);
    }
}
