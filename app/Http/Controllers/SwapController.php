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

        $unhealthyFood = [
            'name' => $product['product_name'] ?? 'Unknown',
            'image' => $product['image_url'] ?? 'https://placehold.co/400',
            'calories' => $product['nutriments']['energy-kcal_100g'] ?? 0,
            'sugar' => $product['nutriments']['sugars_100g'] ?? 0,
            'fat' => $product['nutriments']['fat_100g'] ?? 0,
        ];

        $rawTags = [];

        $rawTags = array_merge($rawTags, $product['categories_tags'] ?? []);
        $rawTags = array_merge($rawTags, $product['labels_tags'] ?? []);
        $rawTags = array_merge($rawTags, $product['brands_tags'] ?? []);

        $textBlob = strtolower(
            ($product['product_name'] ?? '') . ' ' .
            ($product['generic_name'] ?? '') . ' ' .
            ($product['brands'] ?? '') . ' ' .
            ($product['categories'] ?? '') . ' ' .
            ($product['ingredients_text'] ?? '')
        );

        // normalisasi tags: buang "en:" dan jadikan lowercase
        $tags = array_map(function ($tag) {
            $tag = strtolower($tag);
            $tag = str_replace(['en:', 'id:'], '', $tag);
            $tag = str_replace('_', '-', $tag);
            return $tag;
        }, $rawTags);

        $haystack = implode(' ', $tags) . ' ' . $textBlob;

        $rules = SwapRule::with('category')->get();

        $matchedCategory = null;

        foreach ($rules as $rule) {
            if (!$rule->api_keyword) continue;

            $keyword = strtolower(trim($rule->api_keyword));
            $keyword = str_replace('_', '-', $keyword);

            $keywordSpaced = str_replace('-', ' ', $keyword);

            if (str_contains($haystack, $keyword) || str_contains($haystack, $keywordSpaced)) {
                $matchedCategory = $rule->category;
                break;
            }
        }

        // JIKA TIDAK ADA RULE MATCH
        if (!$matchedCategory) {
            return view('swap.result', [
                'unhealthyFood' => array_merge($unhealthyFood, ['tags' => $tags]),
                'matchedCategory' => null,
                'primarySwap' => null,
                'healthySuggestions' => [],
                'comparison' => [],
                'debug_haystack' => $haystack,
            ]);
        }

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
                'score' => 85,
            ];
        }

        return view('swap.result', [
            'unhealthyFood' => array_merge($unhealthyFood, ['tags' => $tags]),
            'matchedCategory' => $matchedCategory,
            'primarySwap' => $primarySwap,
            'comparison' => $comparison,
            'healthySuggestions' => $healthySuggestions,
            'debug_haystack' => $haystack,
        ]);
    }
}
