<?php

namespace App\Services\Providers;

use App\Services\Contracts\ProductProvider;
use Illuminate\Support\Facades\Http;

class OpenFoodFactsProvider implements ProductProvider
{
    protected function mirrors(): array
    {
        $list = trim(env('OFF_MIRRORS', 'world.openfoodfacts.org'));
        return array_values(array_filter(array_map('trim', explode(',', $list))));
    }

    protected function client()
    {
        $timeout  = (int) (config('http.client_timeout', 0) ?: env('HTTP_CLIENT_TIMEOUT', 8));
        $noVerify = filter_var(env('HTTP_DISABLE_SSL_VERIFY', false), FILTER_VALIDATE_BOOL);

        $client = Http::withHeaders([
                'User-Agent' => 'SnackSwap/1.0 (student project)',
                'Accept'     => 'application/json',
            ])
            ->connectTimeout(5)
            ->timeout($timeout)
            ->retry(2, 800);

        if ($noVerify) {
            $client = $client->withOptions(['verify' => false]);
        }

        return $client;
    }

    protected function getFromMirrors(string $path, array $query = [])
    {
        $client  = $this->client();
        $error   = null;

        foreach ($this->mirrors() as $host) {
            $url = "https://{$host}{$path}";
            try {
                $res = $client->get($url, $query);
                if ($res->ok()) {
                    return [$res, null];
                }
                $error = 'http';
            } catch (\Throwable $e) {
                $error = 'timeout';
            }
        }
        return [null, $error ?? 'unknown'];
    }

    public function search(string $query, int $page = 1, int $pageSize = 24, array $opts = []): array
    {
        if (mb_strlen($query) < 2) {
            return ['items' => [], 'next' => null, 'error' => null];
        }

        $pageSize = (int) env('OFF_PAGE_SIZE', $pageSize);

        $fields = implode(',', [
            'code','product_name','brands','image_front_small_url','image_url',
            'categories_tags','countries',
            'nutriments.energy-kcal_100g','nutriments.sugars_100g','nutriments.fat_100g',
            'nutriments.sodium_100g','nutriments.salt_100g',
        ]);

        $params = [
            'search_terms'  => $query,
            'search_simple' => 1,
            'page_size'     => $pageSize,
            'page'          => $page,
            'json'          => 1,
            'fields'        => $fields,
        ];
        if (($opts['region'] ?? null) === 'id') {
            $params += [
                'tagtype_0'      => 'countries',
                'tag_contains_0' => 'contains',
                'tag_0'          => 'Indonesia',
            ];
        }

        [$res, $err] = $this->getFromMirrors('/cgi/search.pl', $params);
        if (!$res) {
            return ['items' => [], 'next' => null, 'error' => $err];
        }

        $json  = $res->json();
        $raw   = $json['products'] ?? [];
        $count = (int)($json['count'] ?? 0);
        $pages = max(1, (int)ceil($count / max(1, $pageSize)));

        $items = collect($raw)->map(function ($p) {
            $sodium_g = data_get($p,'nutriments.sodium_100g');
            $salt_g   = data_get($p,'nutriments.salt_100g');
            if ($sodium_g === null && $salt_g !== null) {
                $sodium_g = 0.393 * (float)$salt_g;
            }
            $sodium_mg = $sodium_g !== null ? (int) round($sodium_g * 1000) : null;

            return [
                'id'       => $p['code'] ?? null,
                'name'     => $p['product_name'] ?? 'Unknown',
                'brand'    => $p['brands'] ?? null,
                'image'    => $p['image_front_small_url'] ?? ($p['image_url'] ?? null),
                'category' => $p['categories_tags'][0] ?? null,
                'calories' => data_get($p, 'nutriments.energy-kcal_100g'),
                'sugar'    => data_get($p, 'nutriments.sugars_100g'),
                'fat'      => data_get($p, 'nutriments.fat_100g'),
                'sodium'   => $sodium_mg,
                'source'   => 'OFF',
                'region'   => $p['countries'] ?? null,
            ];
        })->filter(fn($x)=>$x['id'] || $x['name'])->values()->all();

        $next = $page < $pages ? route('search', [
            'query'=>$query,'page'=>$page+1,'region'=>$opts['region'] ?? 'global'
        ]) : null;

        return ['items'=>$items, 'next'=>$next, 'error'=>null];
    }

    public function detail(string $id): ?array
    {
        [$res, $err] = $this->getFromMirrors("/api/v2/product/{$id}.json");
        if (!$res) return null;
        $p = $res->json('product'); if(!$p) return null;

        $sodium_g = data_get($p, 'nutriments.sodium_100g');
        $salt_g   = data_get($p, 'nutriments.salt_100g');
        if ($sodium_g === null && $salt_g !== null) {
            $sodium_g = 0.393 * (float)$salt_g;
        }
        $sodium_mg = $sodium_g !== null ? (int) round($sodium_g * 1000) : null;

        return [
            'id'       => $p['code'] ?? $id,
            'name'     => $p['product_name'] ?? 'Unknown',
            'brand'    => $p['brands'] ?? null,
            'image'    => $p['image_url'] ?? ($p['image_front_url'] ?? null),
            'category' => $p['categories_tags'][0] ?? null,
            'calories' => data_get($p,'nutriments.energy-kcal_100g'),
            'sugar'    => data_get($p,'nutriments.sugars_100g'),
            'fat'      => data_get($p,'nutriments.fat_100g'),
            'sodium'   => $sodium_mg,
            'source'   => 'OFF',
        ];
    }
}