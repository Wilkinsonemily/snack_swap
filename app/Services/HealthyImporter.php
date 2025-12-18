<?php

namespace App\Services;

use App\Models\Food;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class HealthyImporter
{
    protected function client()
    {
        $timeout = (int) (config('http.client_timeout', 0) ?: env('HTTP_CLIENT_TIMEOUT', 8));
        return Http::withHeaders([
                'User-Agent' => 'SnackSwap/1.0 (student project)',
                'Accept'     => 'application/json',
            ])
            ->connectTimeout(5)
            ->timeout($timeout)
            ->retry(2, 800);
    }

    protected function meetsCriteria(array $n): array
    {
        $reasons = [];

        $cal = Arr::get($n,'energy-kcal_100g');
        $sug = Arr::get($n,'sugars_100g');
        $fat = Arr::get($n,'fat_100g');
        $fib = Arr::get($n,'fiber_100g');
        $pro = Arr::get($n,'proteins_100g');

        $sodium_g = Arr::get($n,'sodium_100g');
        $salt_g   = Arr::get($n,'salt_100g');
        if ($sodium_g === null && $salt_g !== null) {
            $sodium_g = 0.393 * (float)$salt_g;
        }
        $sodium_mg = $sodium_g !== null ? (int) round($sodium_g*1000) : null;

        // batas default
        $okSugar  = $sug !== null ? $sug <= 8 : false;
        $okFat    = $fat !== null ? $fat <= 15 : false;
        $okSodium = $sodium_mg !== null ? $sodium_mg <= 300 : false;
        $okCal    = $cal !== null ? $cal <= 250 : false;

        if ($okSugar)  $reasons[] = "Low sugar (≤8g/100g)";
        if ($okFat)    $reasons[] = "Low fat (≤15g/100g)";
        if ($okSodium) $reasons[] = "Low sodium (≤300mg/100g)";
        if ($okCal)    $reasons[] = "Reasonable calories (≤250kcal/100g)";
        if ($fib !== null && $fib >= 5) $reasons[] = "High fiber (≥5g/100g)";
        if ($pro !== null && $pro >= 10) $reasons[] = "High protein (≥10g/100g)";

        $isHealthy = ($okSugar && $okFat && $okSodium && $okCal) || count($reasons) >= 3;

        return [
            'is_healthy' => $isHealthy,
            'sodium_mg'  => $sodium_mg,
            'reasons'    => $reasons,
        ];
    }

    protected function mapProduct(array $p): ?array
    {
        $code  = Arr::get($p,'code');
        $name  = trim(Arr::get($p,'product_name',''));
        if (!$code || $name === '') return null;

        $brand = Arr::get($p,'brands');
        $img   = Arr::get($p,'image_front_small_url') ?: Arr::get($p,'image_url');
        $cat   = Arr::get($p,'categories_tags.0');
        $catName = $cat ? Str::after($cat, 'en:') : 'other';
        $catSlug = Str::slug($catName);

        $n = Arr::get($p, 'nutriments', []);

        $crit = $this->meetsCriteria($n);
        if (!$crit['is_healthy']) return null;

        $category = Category::firstOrCreate(
            ['slug' => $catSlug],
            ['name' => Str::title(str_replace('-', ' ', $catSlug))]
        );

        return [
            'off_code'  => $code,
            'name'      => $name,
            'brand'     => $brand,
            'image'     => $img,
            'country'   => null,
            'category_id' => $category->id,
            'calories'  => Arr::get($n,'energy-kcal_100g'),
            'sugar'     => Arr::get($n,'sugars_100g'),
            'fat'       => Arr::get($n,'fat_100g'),
            'sodium'    => $crit['sodium_mg'],
            'protein'   => Arr::get($n,'proteins_100g'),
            'fiber'     => Arr::get($n,'fiber_100g'),
            'is_healthy'=> true,
            'is_vegan'  => Str::contains(strtolower(Arr::get($p,'labels','')), 'vegan'),
            'is_gluten_free' => Str::contains(strtolower(Arr::get($p,'labels','')), 'gluten-free'),
            'health_reason' => implode('; ', $crit['reasons']) ?: null,
        ];
    }

    protected function fetchPage(string $query, int $page, int $pageSize = 24, ?string $region = null): array
    {
        $url = 'https://world.openfoodfacts.org/cgi/search.pl';
        $fields = implode(',', [
            'code','product_name','brands','image_front_small_url','image_url',
            'categories_tags','labels',
            'nutriments.energy-kcal_100g','nutriments.sugars_100g','nutriments.fat_100g',
            'nutriments.fiber_100g','nutriments.proteins_100g',
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

        if ($region === 'id') {
            $params += ['tagtype_0'=>'countries','tag_contains_0'=>'contains','tag_0'=>'Indonesia'];
        }

        try {
            $res = $this->client()->get($url, $params);
            if (!$res->ok()) return [];
            return $res->json('products') ?: [];
        } catch (\Throwable) {
            return [];
        }
    }


    public function sync(string $query = 'snack', int $pages = 2, int $pageSize = 24, ?string $region = null): int
    {
        $inserted = 0;

        for ($p = 1; $p <= $pages; $p++) {
            $raw = $this->fetchPage($query, $p, $pageSize, $region);
            foreach ($raw as $prod) {
                $data = $this->mapProduct($prod);
                if (!$data) continue;

                if (!empty($data['off_code'])) {
                    $exists = Food::where('off_code', $data['off_code'])->first();
                    if ($exists) { $exists->update($data); }
                    else { Food::create($data); $inserted++; }
                } else {
                    $exists = Food::where('name',$data['name'])->where('brand',$data['brand'])->first();
                    if ($exists) { $exists->update($data); }
                    else { Food::create($data); $inserted++; }
                }
            }
        }

        return $inserted;
    }
}