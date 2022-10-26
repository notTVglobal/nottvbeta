<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Inertia\Inertia;

class ShowEpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        return Inertia::render('Shows/{$id}/Episode/{$slug}/Index', [
        return Inertia::render('Shows/Index', [

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Show $show)
    {

//        function getPoster($show){
//            $getPoster = Image::query()
//                ->where('show_id', $show->id)
//                ->pluck('name')
//                ->first();
//            if(!empty($getPoster)){
//                $poster = $getPoster;
//            } else {
//                $poster = 'EBU_Colorbars.svg.png';
//            }
//            return $poster;
//        }
//
//        function getTeam($show){
//            return Team::query()
//                ->where('id', $show->team_id)
//                ->first();
//        }

        return Inertia::render('Shows/{$id}/Episode/Create', [
//            'team' => getTeam($show),
            'show' => $show,
//            'poster' => getPoster($show),
//            'teamName' => Team::query()->where('id', $show->team_id)->pluck('name')->firstOrFail(),
//            'showRunner' => User::query()->where('id', $show->user_id)->pluck('name')->firstOrFail(),
//            'can' => [
//                'manageShow' => Auth::user()->can('manage', $show),
//                'editShow' => Auth::user()->can('edit', $show),
//                'viewCreator' => Auth::user()->can('viewCreator', User::class),
//            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $request, Show $show)
    {
        $request->validate([
            'name' => 'unique:shows|required|max:255',
            'description' => 'required',
            'user_id' => 'required',
            'show_id' => 'required',
        ]);
        ShowEpisode::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'show_id' => $request->show_id,
            'slug' => \Str::slug($request->name),
            'isBeingEditedByUser_id' => $request->user_id,
            'image_id' => null,
        ]);

        // Use this route to return
        // the user to the new episode page.

        $episode = ShowEpisode::query()->where('name', $request->name)->pluck('show_id')->firstOrFail();
        return redirect()->route('episode.manage',['id'=>$episode->show_id,'slug'=>$episode->slug])->with('message', 'Episode Created Successfully');
//        return Inertia::render('Shows/{$id}/Manage', $show)->with('message', 'Show Created Successfully');

        // Use this route to return the
        // user back to the team page
        // instead of the new show page.

//      return redirect()->route('teams.manage', $request->team_id)->with('message', 'Show Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $show
     * @return \Illuminate\Http\Response
     */
    // URL path is currently set to show.id
    // change show($id) to show($slug) to
    // make URL path = slug.
    public function show(Show $show, ShowEpisode $showEpisode)
    {
        function getPoster($show){
            $getPoster = Image::query()
                ->where('show_id', $show->id)
                ->pluck('name')
                ->first();
            if(!empty($getPoster)){
                $poster = $getPoster;
            } else {
                $poster = 'EBU_Colorbars.svg.png';
            }
            return $poster;
        }

        return Inertia::render('Shows/{$id}/Episode/{$slug}/Index', [
            'show' => $show,
            'epsiode' => $showEpisode,
            'poster' => getPoster($show),
            'teamName' => Team::query()->where('id', $show->team_id)->pluck('name')->firstOrFail(),
            'showRunner' => User::query()->where('id', $show->user_id)->pluck('name')->firstOrFail(),
            'can' => [
                'manageShow' => Auth::user()->can('manage', $show),
                'editShow' => Auth::user()->can('edit', $show),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $show
     * @return \Illuminate\Http\Response
     */
    // URL path is currently set to show.id
    // change show($id) to show($slug) to
    // make URL path = slug.
    public function manage($id,$slug)
    {
//        function getPoster($show){
//            $getPoster = Image::query()
//                ->where('show_id', $show->id)
//                ->pluck('name')
//                ->first();
//            if(!empty($getPoster)){
//                $poster = $getPoster;
//            } else {
//                $poster = 'EBU_Colorbars.svg.png';
//            }
//            return $poster;
//        }
//        $teamName = Team::query()->where('id', $show->team_id)->pluck('name')->firstOrFail();
//        $showRunner = User::query()->where('id', $show->user_id)->pluck('name')->first();
        echo $id.' - '.$slug;
        {
            return Inertia::render('Shows/{$id}/Episode/{$slug}/Manage', [
                'show'   => $show,
//            'teamName' => $teamName,
                'poster' => getPoster($show),
//            'showRunnerName' => $showRunner,
//            'episodes' => ShowEpisode::query()->where('show_id', $show->id)->get(),
//            'episodes' => Episode::query()
//                ->when(Request::input('search'), function ($query, $search) {
//                    $query->where('name', 'like', "%{$search}%");
//                })
//                ->paginate(10)
//                ->withQueryString()
//                ->through(fn($episode) => [
//                    'id' => $episode->id,
//                    'name' => $episode->name,
//                    'description' => $episode->description,
//                    'notes' => $episode->notes,
//                    'poster' => Image::query()->where('id', $episode->image_id)->pluck('name')->first(),
//                ]),

            ]);
        }
    }

}
