<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Food;
use App\Models\SwapRule;

class SwapController extends Controller
{
    public function show($barcode)
    {
        $response = Http::timeout(10)->get("https://world.openfoodfacts.org/api/v0/product/{$barcode}.json");
        $data = $response->json();

        if (!isset($data['status']) || (int)$data['status'] !== 1) {
            return view('errors.not_found');
        }

        $product = $data['product'] ?? [];

        $unhealthyFood = [
            'name'     => $product['product_name'] ?? ($product['generic_name'] ?? 'Unknown'),
            'image'    => $product['image_url'] ?? 'https://placehold.co/400',
            'calories' => $product['nutriments']['energy-kcal_100g'] ?? 0,
            'sugar'    => $product['nutriments']['sugars_100g'] ?? 0,
            'fat'      => $product['nutriments']['fat_100g'] ?? 0,
        ];

        $texts = [];

        $categoryTags = $product['categories_tags'] ?? [];
        foreach ($categoryTags as $t) {
            $texts[] = $this->normTag($t);
        }

        if (!empty($product['categories'])) {
            $texts[] = strtolower($product['categories']);
        }

        if (!empty($product['product_name'])) $texts[] = strtolower($product['product_name']);
        if (!empty($product['generic_name'])) $texts[] = strtolower($product['generic_name']);

        // combine jadi 1 string besar
        $haystack = implode(' | ', array_filter($texts));

        $rules = SwapRule::with('category')->get();

        $matchedCategory = null;

        foreach ($rules as $rule) {
            $needle = strtolower(trim($rule->api_keyword ?? ''));
            if ($needle === '') continue;

            $needle2 = str_replace(['-', '_'], ' ', $needle);

            if (str_contains($haystack, $needle) || str_contains($haystack, $needle2)) {
                $matchedCategory = $rule->category;
                break;
            }
        }

        if (!$matchedCategory) {
            $healthySuggestions = Food::where('is_healthy', 1)->inRandomOrder()->take(12)->get();
            $primarySwap = $healthySuggestions->first();

            return view('swap.result', [
                'unhealthyFood'      => $unhealthyFood,
                'matchedCategory'    => null,
                'primarySwap'        => $primarySwap,
                'healthySuggestions' => $healthySuggestions,
                'comparison'         => $this->makeComparison($unhealthyFood, $primarySwap),
            ]);
        }

        $healthySuggestions = Food::where('category_id', $matchedCategory->id)
            ->where('is_healthy', 1)
            ->get();

        if ($healthySuggestions->isEmpty()) {
            $healthySuggestions = Food::where('is_healthy', 1)->inRandomOrder()->take(12)->get();
        }

        $primarySwap = $healthySuggestions->first();

        return view('swap.result', [
            'unhealthyFood'      => $unhealthyFood,
            'matchedCategory'    => $matchedCategory,
            'primarySwap'        => $primarySwap,
            'healthySuggestions' => $healthySuggestions,
            'comparison'         => $this->makeComparison($unhealthyFood, $primarySwap),
        ]);
    }

    private function normTag(string $tag): string
    {
        $tag = strtolower($tag);
        $tag = str_replace('en:', '', $tag);
        $tag = str_replace(['-', '_'], ' ', $tag);
        return trim($tag);
    }

    private function makeComparison(array $unhealthyFood, $primarySwap): array
    {
        if (!$primarySwap) return [];

        return [
            'calories_saved' => max(0, ($unhealthyFood['calories'] ?? 0) - ($primarySwap->calories ?? 0)),
            'sugar_saved'    => max(0, ($unhealthyFood['sugar'] ?? 0) - ($primarySwap->sugar ?? 0)),
            'fat_saved'      => max(0, ($unhealthyFood['fat'] ?? 0) - ($primarySwap->fat ?? 0)),
            'score'          => 85,
        ];
    }
}
