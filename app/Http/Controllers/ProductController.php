<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ProductOLD;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Builder[]|Collection
     */
    public function index()
    {
        // return all products
        return ProductOLD::with('categories')
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Shop/ProductOLD/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param ProductOLD $product
     * @return Response
     */
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductOLD $product
     * @return void
     */
    public function edit(ProductOLD $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ProductOLD $product
     * @return void
     */
    public function update(Request $request, ProductOLD $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductOLD $product
     * @return void
     */
    public function destroy(ProductOLD $product)
    {
        //
    }
}
