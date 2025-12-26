<?php

namespace App\Services;

use App\Services\Providers\OpenFoodFactsProvider;
use App\Services\Providers\LocalDatabaseProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FoodCatalogService
{
    /** @return array{items:array,next:?string,error:?string} */
   public function search(string $q, int $page, int $pageSize, array $opts = []): array
    {
        $region = $opts['region'] ?? 'global';

        try {
            $url = "https://world.openfoodfacts.org/cgi/search.pl";

            $response = Http::timeout(12)
                ->retry(2, 300)
                ->withHeaders([
                    'User-Agent' => 'SnackSwap/1.0 (Laravel; Railway)',
                    'Accept' => 'application/json',
                ])
                ->get($url, [
                    'search_terms' => $q,
                    'search_simple' => 1,
                    'action' => 'process',
                    'json' => 1,
                    'page' => $page,
                    'page_size' => $pageSize,
                ]);

            if (!$response->successful()) {
                Log::error("OFF search failed", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'items' => [],
                    'next'  => null,
                    'error' => 'Some external results couldn’t be loaded (API error). Try again.',
                ];
            }

            $data = $response->json();

            $items = collect($data['products'] ?? [])
                ->filter(fn($p) => !empty($p['product_name']))
                ->map(fn($p) => [
                    'id' => $p['code'] ?? null,
                    'name' => $p['product_name'] ?? 'Unknown',
                    'image' => $p['image_url'] ?? null,
                    'calories' => $p['nutriments']['energy-kcal_100g'] ?? null,
                    'sugar' => $p['nutriments']['sugars_100g'] ?? null,
                    'fat' => $p['nutriments']['fat_100g'] ?? null,
                ])
                ->values()
                ->all();

            $hasMore = !empty($data['page_count']) && $page < (int)$data['page_count'];

            return [
                'items' => $items,
                'next'  => $hasMore ? $page + 1 : null,
                'error' => null,
            ];

        } catch (\Throwable $e) {
            Log::error("OFF search timeout/fail", ['msg' => $e->getMessage()]);

            return [
                'items' => [],
                'next'  => null,
                'error' => 'Some external results couldn’t be loaded (timeout). Showing what we have.',
            ];
        }
    }

    public function detail(string $id): ?array
    {
        if (str_starts_with($id, 'local-')) {
            return (new LocalDatabaseProvider())->detail($id);
        }
        return (new OpenFoodFactsProvider())->detail($id);
    }
}