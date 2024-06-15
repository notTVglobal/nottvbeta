<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BrowseController extends Controller
{


////////////  INDEX
///////////////////

  public function index(): \Inertia\Response
  {
    $user = Auth::user();

    $canViewCreator = optional($user)->can('viewCreator', User::class);

    $component = $user ? 'Browse/Index' : 'LoggedOut/Browse/Index';

    return Inertia::render($component, [
        'teams'   => Team::with('user', 'image.appSetting', 'shows', 'teamStatus')
            ->where('is_demo', false)
            ->when(\Illuminate\Support\Facades\Request::input('search'), function ($query, $search) {
              $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(6)
            ->withQueryString()
            ->through(fn($team) => [
                'id'          => $team->id,
                'name'        => $team->name,
                'logo'        => $team->image->name,
                'image'       => $team->image ? (new ImageResource($team->image))->resolve() : null,
                'teamCreator' => $team->user->name,
                'status'      => $team->teamStatus,
                'slug'        => $team->slug,
                'totalShows'  => $team->shows->count(),
                'memberSpots' => $team->members()->count(),
                'totalSpots'  => $team->totalSpots,
                'can'         => [
                    'editTeam' => optional($user)->can('editTeam', $team),
                    'viewTeam' => optional($user)->can('viewTeamManagePage', $team)
                ]
            ]),
        'filters' => request()->only(['search']),
        'can'     => [
            'viewCreator' => $canViewCreator,
        ]
    ]);
  }

  public function fetchTeams(Request $request): \Illuminate\Http\JsonResponse
  {
    $user = $request->user();
    $canViewCreator = optional($user)->can('viewCreator', User::class);

    $teams = Team::with('user', 'image.appSetting', 'shows', 'teamStatus')
        ->where('is_demo', false)
        ->when($request->input('search'), function ($query, $search) {
          $query->where('name', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(6)
        ->withQueryString()
        ->through(fn($team) => [
            'id'          => $team->id,
            'name'        => $team->name,
            'logo'        => $team->image->name,
            'image'       => $team->image ? (new ImageResource($team->image))->resolve() : null,
            'teamCreator' => $team->user->name,
            'status'      => $team->teamStatus,
            'slug'        => $team->slug,
            'totalShows'  => $team->shows->count(),
            'memberSpots' => $team->members()->count(),
            'totalSpots'  => $team->totalSpots,
            'can'         => [
                'editTeam' => optional($user)->can('editTeam', $team),
                'viewTeam' => optional($user)->can('viewTeamManagePage', $team)
            ]
        ]);

    return response()->json([
        'data' => $teams->items(),
        'meta' => [
            'current_page' => $teams->currentPage(),
            'last_page' => $teams->lastPage(),
            'per_page' => $teams->perPage(),
            'total' => $teams->total(),
        ],
    ]);
  }
}
