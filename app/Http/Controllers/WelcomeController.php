<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $session_id = session()->getId();

        return Inertia::render('Welcome', [
//            'canLogin' => Route::has('login'),
//            'canRegister' => Route::has('register'),
            'user_id' => $session_id,
            'logged_out_id' => $session_id,
        ]);
    }
}
