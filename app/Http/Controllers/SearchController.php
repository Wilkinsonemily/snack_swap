<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\FoodCatalogService;

class SearchController extends Controller
{
    public function __construct(private FoodCatalogService $catalog) {}

    public function home()
    {
        return view('home');
    }

    public function index(Request $request)
    {
        $q      = trim($request->query('query',''));
        $page   = max(1, (int) $request->query('page', 1));
        $region = $request->query('region', 'global');

        if ($q === '') {
            return view('search.index', [
                'query'   => '',
                'products'=> [],
                'next'    => null,
                'error'   => null,
                'region'  => $region,
            ]);
        }

        $cacheKey = "search:{$region}:{$page}:" . md5($q);

        // ✅ Ambil dari cache, tapi kalau error jangan dicache lama.
        $res = Cache::get($cacheKey);

        if (!$res) {
            $res = $this->catalog->search(
                $q,
                $page,
                (int) env('OFF_PAGE_SIZE', 12),
                ['region' => $region]
            );

            // kalau error/timeout -> cache sebentar aja (1 menit)
            $ttl = !empty($res['error']) ? now()->addMinutes(1) : now()->addMinutes(15);
            Cache::put($cacheKey, $res, $ttl);
        }

        // log pencarian (kalau table belum ada, dia silent fail)
        try {
            DB::table('search_logs')->insert([
                'query'      => $q,
                'region'     => $region,
                'results'    => is_array($res['items'] ?? null) ? count($res['items']) : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {}

        return view('search.index', [
            'query'    => $q,
            'products' => $res['items'] ?? [],
            'next'     => $res['next'] ?? null,
            'error'    => $res['error'] ?? null,
            'region'   => $region,
        ]);
    }

    public function show(string $id)
    {
        $food = $this->catalog->detail($id);
        abort_if(!$food, 404);

        foreach (['calories','sugar','fat','sodium'] as $k) {
            $food[$k] = $food[$k] ?? null;
        }

        return view('food.show', compact('food'));
    }
}
