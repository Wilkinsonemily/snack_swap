<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Food;
use App\Models\SwapRule;

class SwapController extends Controller
{
    public function show($barcode)
    {
        $response = Http::timeout(12)
            ->retry(2, 300)
            ->withHeaders([
                'User-Agent' => 'SnackSwap/1.0 (Laravel; Railway)',
                'Accept' => 'application/json',
            ])
            ->get("https://world.openfoodfacts.org/api/v0/product/{$barcode}.json");

        $data = $response->json();

        if (!isset($data['status']) || (int) $data['status'] !== 1) {
            return view('errors.not_found');
        }

        $product = $data['product'] ?? [];

        $unhealthyFood = [
            'name'     => $product['product_name'] ?? ($product['generic_name'] ?? 'Unknown'),
            'brand'    => $product['brands'] ?? '',
            'image'    => $product['image_url'] ?? 'https://placehold.co/400',
            'calories' => (float) ($product['nutriments']['energy-kcal_100g'] ?? 0),
            'sugar'    => (float) ($product['nutriments']['sugars_100g'] ?? 0),
            'fat'      => (float) ($product['nutriments']['fat_100g'] ?? 0),
        ];

        $texts = [];

        foreach (($product['categories_tags'] ?? []) as $t) {
            $texts[] = $this->normTag($t);
        }

        if (!empty($product['categories']))   $texts[] = strtolower($product['categories']);
        if (!empty($product['product_name'])) $texts[] = strtolower($product['product_name']);
        if (!empty($product['generic_name'])) $texts[] = strtolower($product['generic_name']);

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

        if ($matchedCategory) {
            $healthySuggestions = Food::where('category_id', $matchedCategory->id)
                ->where('is_healthy', 1)
                ->orderBy('calories', 'asc')
                ->orderBy('sugar', 'asc')
                ->orderBy('fat', 'asc')
                ->get();
        } else {
            $healthySuggestions = Food::where('is_healthy', 1)
                ->orderBy('calories', 'asc')
                ->orderBy('sugar', 'asc')
                ->orderBy('fat', 'asc')
                ->take(12)
                ->get();
        }

        $primarySwap = $healthySuggestions->first();

        if ($matchedCategory && !$primarySwap) {
            $healthySuggestions = Food::where('is_healthy', 1)
                ->orderBy('calories', 'asc')
                ->orderBy('sugar', 'asc')
                ->orderBy('fat', 'asc')
                ->take(12)
                ->get();

            $primarySwap = $healthySuggestions->first();
        }

        if (!$primarySwap) {
            return view('swap.result', [
                'unhealthyFood'      => $unhealthyFood,
                'matchedCategory'    => $matchedCategory,
                'primarySwap'        => null,
                'healthySuggestions' => [],
                'comparison'         => [],
                'message'            => 'No healthy food data available in database.',
            ]);
        }

        if (($primarySwap->calories ?? 0) >= ($unhealthyFood['calories'] ?? 0) && ($unhealthyFood['calories'] ?? 0) > 0) {
            return view('swap.result', [
                'unhealthyFood'      => $unhealthyFood,
                'matchedCategory'    => $matchedCategory,
                'primarySwap'        => null,
                'healthySuggestions' => $healthySuggestions,
                'comparison'         => [],
                'message'            => 'No healthier swap found for this product (calories not lower).',
            ]);
        }

        $score = 100;
        $score -= max(0, ($primarySwap->calories ?? 0) - ($unhealthyFood['calories'] ?? 0)) * 0.2;
        $score -= max(0, ($primarySwap->sugar ?? 0)    - ($unhealthyFood['sugar'] ?? 0))    * 0.5;
        $score -= max(0, ($primarySwap->fat ?? 0)      - ($unhealthyFood['fat'] ?? 0))      * 0.3;
        $score = max(40, min(95, (int) round($score)));

        $comparison = [
            'calories_saved' => max(0, ($unhealthyFood['calories'] ?? 0) - ($primarySwap->calories ?? 0)),
            'sugar_saved'    => max(0, ($unhealthyFood['sugar'] ?? 0)    - ($primarySwap->sugar ?? 0)),
            'fat_saved'      => max(0, ($unhealthyFood['fat'] ?? 0)      - ($primarySwap->fat ?? 0)),
            'score'          => $score,
        ];

        return view('swap.result', compact(
            'unhealthyFood',
            'matchedCategory',
            'primarySwap',
            'comparison',
            'healthySuggestions'
        ));
    }

    private function normTag(string $tag): string
    {
        $tag = strtolower($tag);
        $tag = str_replace(['en:', 'id:'], '', $tag);
        $tag = str_replace(['-', '_'], ' ', $tag);
        return trim($tag);
    }
}