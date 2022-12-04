<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;
use App\Models\Creator;
use function MongoDB\BSON\toJSON;

class CreatorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Creators/Index', [
            'creators' => Creator::join('users AS user', 'creators.user_id', '=', 'user.id')
                ->select('creators.*', 'user.name AS name')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(fn($creator) => [
                    'id' => $creator->id,
                    'name' => $creator->name
                ]),
            'filters' => Request::only(['search'])
        ]);
    }

    /**
     * Display API listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreators(HttpRequest $request)
    {
        $creator = Creator::with('user')->get();
        return $creator;

        $data = Creator::join('users AS user', 'creators.user_id', '=', 'user.id')
            ->select('creators.*', 'user.name AS name')
            ->when(Request::input('search'), function($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%")->get();
            });

        return ([
            'creators' => Creator::join('users AS user', 'creators.user_id', '=', 'user.id')
        ->select('creators.*', 'user.name AS name')
        ->with('user')
        ->when(Request::input('search'), function($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        })
                ->paginate(10)
                ->withQueryString()
                ->through(fn($creator) => [
                    'id' => $creator->user_id,
                    'name' => $creator->user->name
                ]),
            'filters' => Request::only(['search'])
        ])->toJSON();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Creators/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $creator = Creator::query()->where('user_id', $id)->firstOrFail();

        return Inertia::render('Creators/{$id}/Index', [
            'creator' => $creator,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
