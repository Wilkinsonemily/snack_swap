<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Food;
use App\Models\SwapRule;

class SwapSeeder extends Seeder
{
    public function run(): void
    {
        $chips = Category::firstOrCreate(
            ['slug' => 'chips-alternative'],
            ['name' => 'Chips Alternative']
        );

        Food::create([
            'category_id' => $chips->id,
            'name' => 'Roasted Almonds',
            'image' => 'almonds.jpg', 
            'calories' => 579,
            'sugar' => 4.35,
            'fat' => 49.93,
            'sodium' => 0,
            'protein' => 21,
            'fiber' => 12,
            'is_healthy' => true,      
            'is_vegan' => true,
            'health_reason' => 'Almonds provide healthy fats and protein, keeping you full longer than chips.'
        ]);

        SwapRule::create(['api_keyword' => 'chips', 'category_id' => $chips->id]);
        SwapRule::create(['api_keyword' => 'crisps', 'category_id' => $chips->id]);
        SwapRule::create(['api_keyword' => 'potato', 'category_id' => $chips->id]);
    }
}