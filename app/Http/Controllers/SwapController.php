<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Food;
use App\Models\SwapRule;

class SwapController extends Controller
{
    public function show($barcode)
    {
        $response = Http::get("https://world.openfoodfacts.org/api/v0/product/{$barcode}.json");
        $data = $response->json();

        if (!isset($data['status']) || $data['status'] != 1) {
            return view('errors.not_found', ['barcode' => $barcode]);
        }

        $product = $data['product'];
        
        $unhealthyFood = [
            'name' => $product['product_name'] ?? 'Unknown',
            'brand' => $product['brands'] ?? '',
            'image' => $product['image_url'] ?? 'https://placehold.co/400',
            'calories' => $product['nutriments']['energy-kcal_100g'] ?? 0,
            'sugar' => $product['nutriments']['sugars_100g'] ?? 0,
            'fat' => $product['nutriments']['fat_100g'] ?? 0,
            'tags' => $product['categories_tags'] ?? []
        ];

        $matchedCategory = null;
        foreach ($unhealthyFood['tags'] as $tag) {
            $cleanTag = str_replace('en:', '', $tag);
            $rule = SwapRule::where('api_keyword', 'LIKE', '%' . $cleanTag . '%')->first();
            if ($rule) {
                $matchedCategory = $rule->category;
                break;
            }
        }

        $primarySwap = null;
        $healthySuggestions = [];

        if ($matchedCategory) {
            $healthySuggestions = Food::healthy()
                                    ->where('category_id', $matchedCategory->id)
                                    ->get();
            $primarySwap = $healthySuggestions->first();
        }

        $comparison = [];
        if ($primarySwap) {
            $comparison = [
                'calories_saved' => $unhealthyFood['calories'] - $primarySwap->calories,
                'sugar_saved' => $unhealthyFood['sugar'] - $primarySwap->sugar,
                'fat_saved' => $unhealthyFood['fat'] - $primarySwap->fat,
                'score' => 85 
            ];
        }

        return view('swap.result', compact('unhealthyFood', 'matchedCategory', 'primarySwap', 'comparison', 'healthySuggestions'));
    }
}