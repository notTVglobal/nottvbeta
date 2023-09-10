<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Show;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Video;
use App\Policies\AdminPolicy;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);

        function formatBytes($bytes, $precision = 2) {
            $unit = ["B", "KB", "MB", "GB"];
            $exp = floor(log($bytes, 1024)) | 0;
            return round($bytes / (pow(1024, $exp)), $precision).$unit[$exp];
        }

        if (auth()->user()->stripe_id) {
            $hasAccount = true;
        } else
            $hasAccount = false;

        return Inertia::render('Dashboard', [
            // isCreator, isNewsPerson, isVip, isSubscriber
            // need to go in the UserStore. Inertia won't
            // update the initialPage.props, so the solution
            // is to send this data in 3 places the user
            // will land after logging in.
            // 1) The Dashboard
            // 2) Stream
            // 3) any other page from the /login page
            // (AppLayout will be our catch all.)
            // Only the Dashboard has a controller,
            // the others will use Axios to get the data
            // and save it in the UserStore.
            'isAdmin' => auth()->user()->isAdmin,
            'isCreator' => auth()->user()->creator->status_id,
            'isNewsPerson' => auth()->user()->newsPerson,
            'isVip' => auth()->user()->isVip,
            'isSubscriber' => auth()->user()->subscribed('default'),
            'hasAccount' => $hasAccount,
            'shows' => Show::query()
                ->where('user_id', Auth::user()->id)
                ->paginate(5, ['*'], 'shows')
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'slug' => $show->slug,
                    'can' => [
                        'manageShow' => Auth::user()->can('manage', $show)
                    ]
                ]),

            'teams' => $user->teams()->where('active', 1)
                ->paginate(5, ['*'], 'teams')
                ->withQueryString()
                ->through(fn($team) => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'slug' => $team->slug,
                ]),

//            'teams' => User::with('teams')
//                ->where('id', Auth::user()->id)
//                ->paginate(5, ['*'], 'teams_per_page')
//                ->withQueryString()
//                ->through(fn($user) => [
////                    'id' => $team->team->id,
//                    'name' => $user->team->name,
//                    'slug' => $user->team->slug,
//                    'can' => [
//                        'manageTeam' => Auth::user()->can('manage', $team)
//                    ]
//                ]),
            'myTotalStorageUsed' => formatBytes(Video::where('user_id', auth()->user()->id)
                ->sum('size')),
            'notTvTotalStorageUsed' => formatBytes(Video::all()
                ->sum('size')),
            'can' => [
                'viewDashboard' => Auth::user()->can('viewDashboard', Creator::class),
                'viewAdmin' => Auth::user()->can('viewAdmin', User::class),
                'createTeam' => Auth::user()->can('createTeam', Team::class),
                'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
            ]
        ]);
    }
}
