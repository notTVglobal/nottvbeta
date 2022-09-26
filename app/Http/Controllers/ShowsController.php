<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Show;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
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
                    'name' => $show->name
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
        return Inertia::render('Shows/Create');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            // validate the request
            $attributes = Request::validate([
                'name' => 'required',
                'description' => 'required'
            ]);
            // create the user
            Show::create($attributes);
            // redirect
            return redirect('/shows')->with('message', 'Show Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Show $show)
    {
        return Inertia::render('Shows/{$id}/Index', [
            'show' => $show
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
    public function destroy($id)
    {
        //
    }
}
