<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name'=>'Instant Noodles','slug'=>'instant-noodles'],
            ['name'=>'Chips','slug'=>'chips'],
            ['name'=>'Soft Drinks','slug'=>'soft-drinks'],
            ['name'=>'Cookies','slug'=>'cookies'],
            ['name'=>'Chocolate','slug'=>'chocolate'],
            ['name'=>'Cereals','slug'=>'cereals'],
        ];

        foreach ($data as $c) {
            Category::firstOrCreate(['slug'=>$c['slug']], $c);
        }
    }
}