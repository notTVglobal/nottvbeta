<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return Inertia::render('Dashboard', [
            'can' => [
                'viewDashboard' => Auth::user()->can('viewDashboard', User::class),
                'viewAdmin' => Auth::user()->can('viewAdmin', User::class),
            ]
        ]);
    }
}
