<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use App\Models\Show;
use App\Models\Team;
use App\Models\User;
use http\QueryString;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Validation\Rule;
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
        function teamOwner($teamId) {
            $teamOwner = Team::query()->where('id', $teamId)->first();
            return $teamOwner->name;
        }

        function poster($imageId) {
            $poster = Image::query()->where('id', $imageId)->first();
            return $poster->name;
        }

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
                    'team_id' => $show->team_id,
                    'teamOwner' => teamOwner($show->team_id),
                    'slug' => $show->slug,
                    'poster' => poster($show->image_id),
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
            'name' => 'unique:shows|required|max:255',
//            'name' => ['required', 'string', 'max:255', Rule::unique('shows')->ignore($show->id)],
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

        // Use this route to return
        // the user to the new show page.

        $showId = Show::query()->where('name', $request->name)->firstOrFail();
        return redirect()->route('shows.manage', $showId)->with('message', 'Show Created Successfully');

        // Use this route to return the
        // user back to the team page
        // instead of the new show page.

//      return redirect()->route('teams.manage', $request->team_id)->with('message', 'Show Created Successfully');
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

        function poster($imageId) {
            $poster = Image::query()->where('id', $imageId)->first();
            return $poster->name;
        }

        return Inertia::render('Shows/{$id}/Index', [
            'show' => $show,
            'poster' => poster($show->image_id),
            'team' => Team::query()->where('id', $team)->firstOrFail(),
            'showRunner' => User::query()->where('id', $show->user_id)->pluck('name')->firstOrFail(),
            'can' => [
                'manageShow' => Auth::user()->can('manage', Show::class),
                'editShow' => Auth::user()->can('edit', Show::class),
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
    public function manage(Show $show)
    {
        $team = Show::query()->where('id', $show->id)->pluck('team_id')->firstOrFail();

        return Inertia::render('Shows/{$id}/Manage', [
            'show' => $show,
            'team' => Team::query()->where('id', $team)->firstOrFail(),
            'poster' => Image::query()->where('id', $show->image_id)->pluck('name')->first(),
            'showRunner' => User::query()->where('id', $show->user_id)->pluck('name')->firstOrFail(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $show
     * @return \Illuminate\Http\Response
     */
    public function edit(Show $show)
    {
        $team = Show::query()->where('id', $show->id)->pluck('team_id')->firstOrFail();

        return Inertia::render('Shows/{$id}/Edit', [
            'show' => $show,
//            'poster' => poster($show->image_id),
            'poster' => Image::query()->where('id', $show->image_id)->pluck('name')->first(),
            'team' => Team::query()->where('id', $team)->firstOrFail(),
            'images' => Image::query()
                ->latest()
                ->paginate(10)
                ->withQueryString()
                ->through(fn($image) => [
                    'id' => $image->id,
                    'name' => $image->name,
                    'extension' => $image->extension
                ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HttpRequest $request, Show $show)
    {

        // validate the request
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('shows')->ignore($show->id)],
            'description' => 'required',
            'image_id' => 'required',
        ]);

        // update the show
        $show->name = $request->name;
        $show->description = $request->description;
        $show->image_id = $request->image_id;
        $show->save();
        sleep(1);

        // gather the data needed to render the Manage page
        // this is all redundant. It's all contained in the
        // teams.manage route above. But I (tec21) don't know
        // how to simplify this *frustrated*.

        return Inertia::render('Shows/{$id}/Manage', [
            // responses need to be limited to only
            // the information required with ->only()
            // https://inertiajs.com/responses
            'show' => $show,
            'poster' => Image::query()->where('id', $show->image_id)->pluck('name')->first(),
            'team' => Team::query()->where('id', $show->team_id)->pluck('id','name')->firstOrFail(),
            'showRunner' => User::query()->where('id', $show->user_id)->pluck('name')->firstOrFail(),
        ])->with('message', 'Show Updated Successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function uploadPoster(Request $request)
    {
        $request->file('image')->store('images');

////        $files = Storage::disk('spaces')->files('uploads');
////        function() {
////            Storage::disk('spaces')->putFile('uploads', request()->file, 'public');
////        };
//
//        if (!$path) {
//            return response()->json(['error' => 'The file could not be saved.'], 500);
//        }
        $user = auth()->user()->id;
        $uploadedFile = $request->file('image');
        $poster = $uploadedFile->hashName();
        // create image model
        // NEED TO PROTECT THESE
        Image::create([
            'name' => $poster,
            'extension' => $uploadedFile->extension(),
            'size' => $uploadedFile->getSize(),
            'user_id' => $user,
        ]);

        // get new image_id

        // store image_id to Shows table

        // update Image on frontend
        // return that image model back to the frontend
//        return DB::table('images')->where('name', $poster)->pluck('id')->through(fn($image) => [
//            'image_id' => $image->id,
//            'poster' => $poster,
//        ]);

//        return $poster;
        return Inertia::render('Shows/{$id}/Edit');

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
