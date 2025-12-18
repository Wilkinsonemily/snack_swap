<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (Schema::hasTable('foods')) {

            $count = \DB::table('foods')->count();

            if ($count === 0) {
                Artisan::call('healthy:sync', ['type' => 'snack']);
            }
        }
    }
}
