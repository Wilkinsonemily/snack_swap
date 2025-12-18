<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Food extends Model
{
    protected $table = 'foods';

    protected $fillable = [
    'off_code','name','brand','image','country','category_id',
    'calories','sugar','fat','sodium','protein','fiber',
    'is_healthy','is_vegan','is_gluten_free',
    ];

    protected $casts = [
    'is_healthy'=>'boolean','is_vegan'=>'boolean','is_gluten_free'=>'boolean',
    'calories'=>'float','sugar'=>'float','fat'=>'float','protein'=>'float','fiber'=>'float','sodium'=>'integer',
    ];

    public function category(){ return $this->belongsTo(Category::class); }

    public function scopeHealthy(Builder $q): Builder { return $q->where('is_healthy', true); }
    public function scopeLowSugar(Builder $q, float $max=8): Builder { return $q->whereNotNull('sugar')->where('sugar','<=',$max); }
    public function scopeHighProtein(Builder $q, float $min=10): Builder { return $q->whereNotNull('protein')->where('protein','>=',$min); }
    public function scopeHighFiber(Builder $q, float $min=5): Builder { return $q->whereNotNull('fiber')->where('fiber','>=',$min); }
}