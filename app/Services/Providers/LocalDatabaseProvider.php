<?php

namespace App\Services\Providers;

use App\Services\Contracts\ProductProvider;
use App\Models\Food;
use Illuminate\Support\Facades\Schema;

class LocalDatabaseProvider implements ProductProvider
{
    public function search(string $query, int $page = 1, int $pageSize = 24, array $opts = []): array
    {
        $limit = (int)($opts['limit'] ?? $pageSize);

        $q = Food::with('category')
            ->where(function($qq) use ($query){
                $qq->where('name','like',"%$query%")
                   ->orWhere('brand','like',"%$query%");
            });
            
        if (Schema::hasColumn('foods','country')) {
            $q->orderByRaw("CASE WHEN country='ID' THEN 0 ELSE 1 END");
        }

        $rows = $q->limit($limit)->get();

        $items = $rows->map(function($f){
            return [
                'id'       => 'local-'.$f->id,
                'name'     => $f->name,
                'brand'    => $f->brand,
                'image'    => $f->image,
                'category' => optional($f->category)->slug,
                'calories' => $f->calories,
                'sugar'    => $f->sugar,
                'fat'      => $f->fat,
                'sodium'   => $f->sodium,
                'source'   => 'LOCAL',
                'region'   => $f->country,
            ];
        })->all();

        return ['items'=>$items, 'next'=>null, 'error'=>null];
    }

    public function detail(string $id): ?array
    {
        $row = Food::with('category')->find(ltrim($id,'local-'));
        if (!$row) return null;

        return [
            'id'       => 'local-'.$row->id,
            'name'     => $row->name,
            'brand'    => $row->brand,
            'image'    => $row->image,
            'category' => optional($row->category)->slug,
            'calories' => $row->calories,
            'sugar'    => $row->sugar,
            'fat'      => $row->fat,
            'sodium'   => $row->sodium,
            'source'   => 'LOCAL',
        ];
    }
}