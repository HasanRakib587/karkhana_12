<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'slug',        
        'image',
        'is_active'
    ];

    public function products() {
        return $this->hasMany(Product::class);
    }    
}
