<?php

namespace App\Http\Controllers;

use App\Models\NewsStory;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChannelController extends Controller
{

    // TODO: Setup the CRUD pages in Vue

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {

//Channel::with(['externalSource', 'channelPlaylist', 'mistStream'])->get();

        return Inertia::render('Admin/Channels/Index', [
            'channels' => Channel::with(['externalSource', 'channelPlaylist', 'mistStream'])
                ->when(Request::input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })  ->latest()
                ->paginate(13, ['*'], 'admin/channels')
                ->withQueryString()
                ->through(fn($channel) => [
                        'id' => $channel->id,
                        'name' => $channel->name,
                        'channelPlaylist' => $channel->channelPlaylist ?? null,
                        'mistStream' => $channel->mistStream ?? null,
                        'externalSource' => $channel->externalSource ?? null,
                    ]),
            'filters' => Request::only(['search']),
        ]);
    }


  public function search($type, Request $request)
  {
    // Logic to fetch items based on the type and search term
    // Return Inertia response or JSON, depending on your setup
  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
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
