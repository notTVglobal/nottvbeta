<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductOLD;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductControllerOLD extends Controller
{
    public function index()
    {
        // return all products
        return ProductOLD::with('categories:id,name')
            ->get();
    }

    public function show(ProductOLD $product)
    {
        return Inertia::render('Shop/ProductOLD/{$id}/Index', [
            'product' => [
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'categories' => $product->categories,
            ],
        ]);
    }
}
