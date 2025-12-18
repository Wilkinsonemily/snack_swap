<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    public function foods() {
        return $this->hasMany(Food::class);
    }

    public function rules() {
        return $this->hasMany(SwapRule::class);
    }
}