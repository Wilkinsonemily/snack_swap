<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Pastikan table users ada
        if (Schema::hasTable('users')) {

            // Cek apakah admin sudah ada
            $adminExists = DB::table('users')
                ->where('email', 'admin@example.com')
                ->exists();

            if (! $adminExists) {
                DB::table('users')->insert([
                    'name'              => 'Admin',
                    'email'             => 'admin@example.com',
                    'password'          => Hash::make('admin123'),
                    'is_admin'          => 1,
                    'email_verified_at' => now(),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }
    }
}
