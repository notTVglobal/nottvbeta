<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopControllerDELETE extends Controller
{
    public function index()
    {
        return Inertia::render('Shop/Index', [
//            'can' => [
//                'viewVip' => Auth::user()->can('viewCreator', User::class),
//            ]
        ]);
    }

    public function checkout(Shop $shop)
    {
        return Inertia::render('Shop/Checkout/Index', [
//            'can' => [
//                'viewVip' => Auth::user()->can('viewCreator', User::class),
//            ]
        ]);
    }
}
