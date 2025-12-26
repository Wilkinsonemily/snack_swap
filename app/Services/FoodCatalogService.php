<?php

namespace App\Services;

use App\Services\Providers\OpenFoodFactsProvider;
use App\Services\Providers\LocalDatabaseProvider;

class FoodCatalogService
{
    /** @return array{items:array,next:?string,error:?string} */
    public function search(string $query, int $page = 1, int $size = 24, array $opts = []): array
    {
        $region = $opts['region'] ?? 'global';

        $providers = $region === 'id'
            ? [new LocalDatabaseProvider(), new OpenFoodFactsProvider()]
            : [new OpenFoodFactsProvider(), new LocalDatabaseProvider()];

        $all = []; $next = null; $error = null;

        foreach ($providers as $i => $p) {
            $limit = $size;                    // boleh dibagi, mis. $size/2
            $res = $p->search($query, $page, $size, ['region'=>$region, 'limit'=>$limit]);
            $all   = array_merge($all, $res['items']);
            $next  = $next  ?? $res['next'];
            $error = $error ?? $res['error'];
        }

        $seen = [];
        $dedup = [];
        foreach ($all as $it) {
            $key = $it['id']
                ?: strtolower(trim(($it['name'] ?? '').'|'.($it['brand'] ?? '')));
            if (!$key || isset($seen[$key])) continue;
            $seen[$key] = true;
            $dedup[] = $it;
        }

        if ($region === 'global') {
            usort($dedup, fn($a,$b) => ($a['source']==='OFF'?0:1) <=> ($b['source']==='OFF'?0:1));
        } else {
            usort($dedup, fn($a,$b) => ($a['source']==='LOCAL'?0:1) <=> ($b['source']==='LOCAL'?0:1));
        }

        return ['items'=>array_slice($dedup, 0, $size), 'next'=>$next, 'error'=>$error];
    }

    public function detail(string $id): ?array
    {
        if (str_starts_with($id, 'local-')) {
            return (new LocalDatabaseProvider())->detail($id);
        }
        return (new OpenFoodFactsProvider())->detail($id);
    }
}