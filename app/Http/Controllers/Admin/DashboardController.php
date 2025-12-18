<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Category;
use App\Models\SwapRule;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalFoods' => Food::count(),
            'totalCategories' => Category::count(),
            'totalRules' => SwapRule::count(),
            'recentFoods' => Food::with('category')->latest()->take(5)->get()
        ]);
    }
}