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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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
        return Inertia::render('Shows/{$id}/Episode/{$id}/Index', [

        ]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $request)
    {
        $request->validate([
            'name' => 'unique:shows|required|max:255',
            'description' => 'required',
            'user_id' => 'required',
            'show_id' => 'required',
        ]);
        $showEpisode = new ShowEpisode();
        $showEpisode->name = $request->name;
        $showEpisode->description = $request->description;
        $showEpisode->user_id = Auth::user()->id;
        $showEpisode->show_id = $request->show_id;
        $showEpisode->slug = \Str::slug($request->name);
        $showEpisode->isBeingEditedByUser_id = Auth::user()->id;
        $showEpisode->save();

        $showSlug = $request->show_slug;
        $showEpisodeSlug = $showEpisode->slug;

        // Use this route to return
        // the user to the new episode page.

//        $episode = ShowEpisode::query()
//            ->where('name', $request->name)
//            ->pluck('show_id')
//            ->firstOrFail();

        return redirect()
            ->route('shows.showEpisodes.manageEpisode',
                [$showSlug,$showEpisodeSlug])
            ->with('message', 'Episode Created Successfully');

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
        echo $show.' - '.$showEpisode;
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

            return Inertia::render('Shows/{$id}/Episode/{$id}/Index', [
                'show' => $show,
                'episode' => $showEpisode,
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
    }



}
