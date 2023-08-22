<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_product', 'product_id', 'category_id', 'id')
            ->as('categories');
    }

    public function getRouteKeyName() {
        return 'slug';
    }
}
