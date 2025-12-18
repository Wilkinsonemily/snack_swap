<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;

class HealthyCatalogController extends Controller
{
    public function index(Request $request)
    {
        $q       = trim($request->query('q',''));
        $badge   = $request->query('badge');
        $sort    = $request->query('sort','name_asc');
        $perPage = (int) $request->query('per', 12);

        $query = Food::healthy();

        if ($q !== '') {
            $query->where(fn($w) => $w
                ->where('name','like',"%$q%")
                ->orWhere('brand','like',"%$q%")
            );
        }

        switch ($badge) {
            case 'low_sugar':    $query->lowSugar(); break;
            case 'high_protein': $query->highProtein(); break;
            case 'high_fiber':   $query->highFiber(); break;
            case 'vegan':        $query->where('is_vegan', true); break;
            case 'gluten_free':  $query->where('is_gluten_free', true); break;
        }

        match ($sort) {
            'cal_asc'  => $query->orderBy('calories','asc'),
            'cal_desc' => $query->orderBy('calories','desc'),
            default    => $query->orderBy('name','asc'),
        };

        $foods = $query->paginate($perPage)->withQueryString();

        return view('healthy.index', compact('foods','q','badge','sort','perPage'));
    }

    public function show(int $id)
    {
        $food = Food::healthy()->findOrFail($id);
        return view('healthy.show', compact('food'));
    }
}