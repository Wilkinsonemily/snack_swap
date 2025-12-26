<?php

namespace App\Http\Controllers;

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
            return view('errors.not_found');
        }

        $product = $data['product'];

        // 🔹 ORIGINAL PRODUCT
        $unhealthyFood = [
            'name' => $product['product_name'] ?? 'Unknown',
            'image' => $product['image_url'] ?? 'https://placehold.co/400',
            'calories' => $product['nutriments']['energy-kcal_100g'] ?? 0,
            'sugar' => $product['nutriments']['sugars_100g'] ?? 0,
            'fat' => $product['nutriments']['fat_100g'] ?? 0,
            'tags' => array_map(
                fn($tag) => strtolower(str_replace('en:', '', $tag)),
                $product['categories_tags'] ?? []
            )
        ];

        // 🔹 AMBIL SEMUA RULE SEKALI (BIAR CEPAT & STABIL)
        $rules = SwapRule::with('category')->get();

        $matchedCategory = null;

        foreach ($rules as $rule) {
            foreach ($unhealthyFood['tags'] as $tag) {
                if (str_contains($tag, strtolower($rule->api_keyword))) {
                    $matchedCategory = $rule->category;
                    break 2;
                }
            }
        }

        // 🔹 JIKA TIDAK ADA RULE MATCH
        if (!$matchedCategory) {
            return view('swap.result', [
                'unhealthyFood' => $unhealthyFood,
                'matchedCategory' => null,
                'primarySwap' => null,
                'healthySuggestions' => [],
                'comparison' => []
            ]);
        }

        // 🔹 AMBIL MAKANAN SEHAT SESUAI CATEGORY
        $healthySuggestions = Food::where('category_id', $matchedCategory->id)
            ->where('is_healthy', 1)
            ->get();

        $primarySwap = $healthySuggestions->first();

        $comparison = [];
        if ($primarySwap) {
            $comparison = [
                'calories_saved' => max(0, $unhealthyFood['calories'] - $primarySwap->calories),
                'sugar_saved' => max(0, $unhealthyFood['sugar'] - $primarySwap->sugar),
                'fat_saved' => max(0, $unhealthyFood['fat'] - $primarySwap->fat),
                'score' => 85
            ];
        }

        return view('swap.result', compact(
            'unhealthyFood',
            'matchedCategory',
            'primarySwap',
            'comparison',
            'healthySuggestions'
        ));
    }
}
