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
      //
    }


  public function search($type, Request $request)
  {
    // Logic to fetch items based on the type and search term
    // Return Inertia response or JSON, depending on your setup
  }


  public function setPlaybackPriorityType(Channel $channel, HttpRequest $request)
  {
    //
    $validatedData = $request->validate([
//        'channelId' => 'unique:teams|required|max:255',
        'setPriorityType' => 'required|nullable|string|max:15',
    ]);

//    $channelId = $validatedData->channelId;
    $priorityType = $validatedData['setPriorityType'];
//    $channel = Channel::where('id', $channelId)->first();
    $channel->playback_priority_type = $priorityType;
    $channel->save();

//    return redirect()->route('admin.channels', $channel)->with('message', 'Channel '. $channel->id .'Priority Successfully Changed');
    // Return a JSON response
    return response()->json([
        'success' => true,
        'message' => 'Channel ' . $channel->id . ' Priority Successfully Changed',
        'channel' => $channel,
    ]);

  }

  public function setMistStream(Channel $channel, HttpRequest $request)
  {
    $validatedData = $request->validate([
        'mistStreamId' => 'required|nullable|string',
    ]);

    $mistStreamId = $validatedData['mistStreamId'];
    $channel->mist_stream_id = $mistStreamId;
    $channel->save();

    // Return a JSON response
    return response()->json([
        'success' => true,
        'message' => 'Channel ' . $channel->id . ' Mist Stream Successfully Changed',
        'channel' => $channel,
    ]);
  }

  public function setChannelPlaylist(Channel $channel, HttpRequest $request)
  {
    $validatedData = $request->validate([
        'channelPlaylistId' => 'required|nullable|integer',
    ]);

    $channelPlaylistId = $validatedData['channelPlaylistId'];
    $channel->channel_playlist_id = $channelPlaylistId;
    $channel->save();

    // Return a JSON response
    return response()->json([
        'success' => true,
        'message' => 'Channel ' . $channel->id . ' Channel Playlist Successfully Changed',
        'channel' => $channel,
    ]);
  }

  public function setExternalSource(Channel $channel, HttpRequest $request)
  {
    $validatedData = $request->validate([
        'externalSourceId' => 'required|nullable|integer',
    ]);

    $externalSourceId = $validatedData['externalSourceId'];
    $channel->channel_external_source_id = $externalSourceId;
    $channel->save();

    // Return a JSON response
    return response()->json([
        'success' => true,
        'message' => 'Channel ' . $channel->id . ' Channel External Source Successfully Changed',
        'channel' => $channel,
    ]);
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
