<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HealthyCatalogController;
use App\Http\Controllers\SwapController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\RuleController;

Route::get('/', [SearchController::class,'home'])->name('home');
Route::get('/search', [SearchController::class,'index'])->name('search');
Route::get('/food/{id}', [SearchController::class,'show'])->name('food.show');

Route::get('/healthy', [HealthyCatalogController::class, 'index'])->name('healthy.index');
Route::get('/healthy/{id}', [HealthyCatalogController::class, 'show'])->name('healthy.show');

Route::get('/swap/{barcode}', [SwapController::class, 'show'])->name('swap.result');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('foods', FoodController::class);
    Route::resource('rules', RuleController::class);

});

