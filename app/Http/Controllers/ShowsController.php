<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use App\Models\Show;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;

class ShowsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Shows/Index', [
            'shows' => Show::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'slug' => $show->slug,
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'viewShows' => Auth::user()->can('view', Show::class),
                'createShow' => Auth::user()->can('create', Show::class),
                'editShow' => Auth::user()->can('edit', Show::class)
            ]
        ]);
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $team = \Str::slug($request);

//        return Inertia::render('Shows/Create');
             return Inertia::render('Shows/Create', [
            'teamName' => Team::query()->where('id', '1')->firstOrFail(),
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
//            // validate the request
//            $attributes = Request::validate([
//                'name' => 'unique:shows|required',
//                'description' => 'required',
//                'user_id' => 'required',
//                'team_id' => 'required',
//                'slug' => 'required',
//            ]);
//            // create the user
//            Show::create($attributes);
//            // redirect
//            return redirect('/shows')->with('message', 'Show Created Successfully');

        $request->validate([
            'name' => 'unique:shows|required',
            'description' => 'required',
            'user_id' => 'required',
            'team_id' => 'required',
        ]);
        Show::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'team_id' => $request->team_id,
            'slug' => \Str::slug($request->name),
        ]);
        return redirect()->route('teams.show', $request->team_id)->with('message', 'Show Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // URL path is currently set to show.id
    // change show($id) to show($slug) to
    // make URL path = slug.
    public function show($id)
    {
        $show = Show::query()->where('id', $id)->firstOrFail();
        $team = $show->team_id;

        return Inertia::render('Shows/{$id}/Index', [
            'show' => $show,
            'team' => Team::query()->where('id', $team)->firstOrFail(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Show $show, Image $image)
    {
        return Inertia::render('Shows/{$id}/Edit', [
            'show' => $show,
            'images' => Image::query()
        ]);

        // return that image model back to the frontend
        return $image;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Show $show)
    {


        // validate the request
        $attributes = Request::validate([
            'name' => 'required',
            'description' => 'required',
            'poster'
        ]);
        // update the show
        $show->update($attributes);

        // redirect
        return Inertia::render('Shows/{$id}/Index', [
            'show' => $show
        ])->with('message', 'Show Updated Successfully');

//        $request->validate([
//            'name' => 'required|string|max:255',
//            'description' => 'required',
//        ]);
//
//        $show->name = $request->name;
//        $show->description = $request->description;
//        $show->save();
//        sleep(1);
//
//        return Inertia::render('Shows', [
//            'show' => $show
//        ])->with('message', 'Show Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Show $show)
    {
        $show->delete();
        sleep(1);

        // redirect
        return redirect()->route('shows')->with('message', 'Show Deleted Successfully');
    }
}
