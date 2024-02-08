<?php

namespace App\Http\Controllers;

use App\Models\ChannelPlaylist;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChannelPlaylistController extends Controller
{

  public function __construct() {

    $this->middleware('can:viewAny,' . ChannelPlaylist::class)->only(['index']);
    $this->middleware('can:view,channelPlaylist')->only(['show']);
    $this->middleware('can:create,' . ChannelPlaylist::class)->only(['create']);
    $this->middleware('can:store,' . ChannelPlaylist::class)->only(['store']);
    $this->middleware('can:edit,channelPlaylist')->only(['edit']);
    $this->middleware('can:update,channelPlaylist')->only(['update']);
    $this->middleware('can:delete,channelPlaylist')->only(['delete']);
    $this->middleware('can:restore,channelPlaylist')->only(['restore']);
    $this->middleware('can:forceDelete,channelPlaylist')->only(['forceDelete']);
  }


  public function adminSearchChannelPlaylists(Request $request)
  {
    $search = $request->input('search', '');

    // Directly fetch mistStreams based on the search input.
    $channelPlaylists = ChannelPlaylist::query()
        ->when($search, function ($query, $search) {
          // Use the $search variable captured from the request
          return $query->where('name', 'like', "%{$search}%");
        })
        ->get();

    $filters = [
        'search' => $search, // Use the $search variable you've already set
    ];

    return Inertia::render('Admin/Channels/Index', [
        'channelPlaylists' => $channelPlaylists, // Pass the fetched mistStreams directly
        'channelPlaylistsSearchFilters' => $filters,
    ]);

  }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
      $mistStreams = ChannelPlaylist::all();
      return response()->json($mistStreams);
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
     * @param  \App\Models\ChannelPlaylist  $channelPlaylist
     * @return \Illuminate\Http\Response
     */
    public function show(ChannelPlaylist $channelPlaylist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChannelPlaylist  $channelPlaylist
     * @return \Illuminate\Http\Response
     */
    public function edit(ChannelPlaylist $channelPlaylist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChannelPlaylist  $channelPlaylist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChannelPlaylist $channelPlaylist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChannelPlaylist  $channelPlaylist
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChannelPlaylist $channelPlaylist)
    {
        //
    }
}
