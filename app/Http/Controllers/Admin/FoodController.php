<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    public function index()
    {
        // Tampilkan hanya yang sehat (menggunakan scopeHealthy punya Person A)
        $foods = Food::healthy()->with('category')->latest()->paginate(10);
        return view('admin.foods.index', compact('foods'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.foods.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'image' => 'required|image|max:2048', // Max 2MB
            'calories' => 'required|numeric',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('foods', 'public'); 
        }
        
        Food::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'image' => $path,
            'calories' => $request->calories,
            'sugar' => $request->sugar,
            'fat' => $request->fat,
            'sodium' => $request->sodium,
            'protein' => $request->protein,
            'fiber' => $request->fiber,
            'health_reason' => $request->health_reason,
            'is_healthy' => true, 
            'is_vegan' => $request->has('is_vegan'),
            'is_gluten_free' => $request->has('is_gluten_free'),
        ]);

        return redirect()->route('admin.foods.index')->with('success', 'Food added!');
    }

    public function edit(Food $food)
    {
        $categories = Category::all();
        
        return view('admin.foods.edit', compact('food', 'categories'));
    }

    public function update(Request $request, Food $food)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'calories' => 'required|numeric',
            'image' => 'nullable|image|max:2048', 
        ]);

        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'calories' => $request->calories,
            'sugar' => $request->sugar,
            'fat' => $request->fat,
            'sodium' => $request->sodium,
            'protein' => $request->protein,
            'fiber' => $request->fiber,
            'health_reason' => $request->health_reason,
            'is_vegan' => $request->has('is_vegan'),
            'is_gluten_free' => $request->has('is_gluten_free'),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('foods', 'public');
        }

        $food->update($data);

        return redirect()->route('admin.foods.index')->with('success', 'Food updated successfully!');
    }

    public function destroy(Food $food)
    {
        $food->delete();
        return redirect()->route('admin.foods.index')->with('success', 'Food deleted!');
    }
}