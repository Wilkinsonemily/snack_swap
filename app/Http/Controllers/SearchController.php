<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\FoodCatalogService;

class SearchController extends Controller
{
    public function __construct(private FoodCatalogService $catalog) {}

    public function home() { return view('home'); }

    public function index(Request $request)
    {
        $q      = trim($request->query('query',''));
        $page   = (int) $request->query('page', 1);
        $region = $request->query('region', 'global');

        if ($q === '') {
            return view('search.index', [
                'query'=>'','products'=>[], 'next'=>null, 'error'=>null, 'region'=>$region
            ]);
        }

        $cacheKey = "search:{$region}:{$page}:" . md5($q);
        $res = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($q,$page,$region) {
            return $this->catalog->search($q, $page, (int) env('OFF_PAGE_SIZE', 12), ['region'=>$region]);
        });

        try {
            DB::table('search_logs')->insert([
                'query'      => $q,
                'region'     => $region,
                'results'    => count($res['items']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) { }

        return view('search.index', [
            'query'=>$q,'products'=>$res['items'],'next'=>$res['next'],
            'error'=>$res['error'],'region'=>$region,
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