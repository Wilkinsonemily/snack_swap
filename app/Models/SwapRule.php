<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SwapRule extends Model
{
    protected $guarded = ['id'];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}