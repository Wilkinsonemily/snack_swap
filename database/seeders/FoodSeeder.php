<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Food;
use App\Models\Category;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        $noodle = Category::where('slug','instant-noodles')->first();
        $chips  = Category::where('slug','chips')->first();
        $cookie = Category::where('slug','cookies')->first();

        $foods = [
            [
                'name'=>'Mi Goreng','brand'=>'Indomie','country'=>'ID',
                'image'=>'https://images.openfoodfacts.org/images/products/899/600/160/0063/front_en.19.400.jpg',
                'category_id'=>$noodle?->id,'calories'=>460,'sugar'=>6,'fat'=>17,'sodium'=>880,
            ],
            [
                'name'=>'Keripik Kentang BBQ','brand'=>'Chitato','country'=>'ID',
                'image'=>'https://images.openfoodfacts.org/images/products/899/600/144/0464/front_en.23.400.jpg',
                'category_id'=>$chips?->id,'calories'=>540,'sugar'=>2,'fat'=>34,'sodium'=>600,
            ],
            [
                'name'=>'Oreo Original','brand'=>'Oreo','country'=>'ID',
                'image'=>'https://images.openfoodfacts.org/images/products/762/221/044/9283/front_en.83.400.jpg',
                'category_id'=>$cookie?->id,'calories'=>480,'sugar'=>38,'fat'=>19,'sodium'=>450,
            ],
            [
                'name'=>'Taro Net Seaweed','brand'=>'Taro','country'=>'ID',
                'image'=>'foods/taro-net-seaweed.jpg',
                'category_id'=>$chips?->id,'calories'=>520,'sugar'=>3,'fat'=>30,'sodium'=>650,
            ],
        ];

        foreach ($foods as $f) {
            Food::firstOrCreate(
                ['name'=>$f['name'], 'brand'=>$f['brand']],
                $f
            );
        }
    }
}