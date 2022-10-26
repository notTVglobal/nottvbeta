<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Show;
use App\Models\Team;
use Illuminate\Support\Facades\Request;
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
    public function index()
    {
        return Inertia::render('Dashboard', [
            'shows' => Show::query()
                ->where('user_id', Auth::user()->id)
                ->paginate(5, ['*'], 'shows_per_page')
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'slug' => $show->slug,
                    'can' => [
                        'manageShow' => Auth::user()->can('manage', $show)
                    ]
                ]),
            'teams' => Team::query()
                ->where('user_id', Auth::user()->id)
                ->paginate(5, ['*'], 'teams_per_page')
                ->withQueryString()
                ->through(fn($team) => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'slug' => $team->slug,
                    'can' => [
                        'manageTeam' => Auth::user()->can('manage', $team)
                    ]
                ]),
            'can' => [
                'viewDashboard' => Auth::user()->can('viewDashboard', User::class),
                'viewAdmin' => Auth::user()->can('viewAdmin', User::class),
            ]
        ]);
    }
}
