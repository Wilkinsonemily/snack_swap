<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SwapRule;
use App\Models\Category;
use Illuminate\Http\Request;

class RuleController extends Controller {
    public function index() {
        $rules = SwapRule::with('category')->latest()->get();
        $categories = Category::all();
        return view('admin.rules.index', compact('rules', 'categories'));
    }

    public function store(Request $request) {
        SwapRule::create($request->all());
        return back()->with('success', 'Rule added!');
    }

    public function destroy(SwapRule $rule) {
        $rule->delete();
        return back()->with('success', 'Rule deleted!');
    }
}