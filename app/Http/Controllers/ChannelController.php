<?php

namespace App\Http\Controllers;

use App\Models\ChannelPlaylist;
use App\Models\NewsStory;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ChannelController extends Controller
{

    // TODO: Setup the CRUD pages in Vue

    /**
     * Display a listing of the resource.
     *
     * @return void
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


  public function setPlaybackPriorityType(Channel $channel, HttpRequest $request): JsonResponse
  {
    // Validate the incoming request
    $validatedData = $request->validate([
        'setPriorityType' => 'required|string|max:15|nullable',
    ]);

    // Extract the validated priority type
    $priorityType = $validatedData['setPriorityType'];

    // Update the playback priority type of the channel
    $channel->playback_priority_type = $priorityType;
    $channel->save();

    // Update the status of the associated playlist based on the priority type
    if ($channel->channel_playlist_id) {
      $channelPlaylist = ChannelPlaylist::find($channel->channel_playlist_id);
      if ($channelPlaylist) {
        if ($priorityType === 'channelPlaylist') {
          $channelPlaylist->status = 'Active';
        } else {
          // Check if any other channels are using the same playlist with 'channelPlaylist' priority type
          $otherChannels = Channel::where('channel_playlist_id', $channel->channel_playlist_id)
              ->where('id', '!=', $channel->id)
              ->where('playback_priority_type', 'channelPlaylist')
              ->exists();

          if ($otherChannels) {
            $channelPlaylist->status = 'Active';
          } else {
            $channelPlaylist->status = 'Standby';
          }
        }
        $channelPlaylist->save();
      }
    }

    // Return a JSON response
    return response()->json([
        'success' => true,
        'message' => 'Channel ' . $channel->id . ' Priority Successfully Changed',
        'channel' => $channel,
    ]);
  }

  public function setMistStream(Channel $channel, HttpRequest $request): JsonResponse {
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

  public function setChannelPlaylist(Channel $channel, HttpRequest $request): JsonResponse
  {
    $validatedData = $request->validate([
        'channelPlaylistId' => 'required|integer|nullable',
    ]);

    $newChannelPlaylistId = $validatedData['channelPlaylistId'];
    $currentChannelPlaylistId = $channel->channel_playlist_id;

    // Update the status of the selected playlist
    if ($newChannelPlaylistId) {
      $selectedPlaylist = ChannelPlaylist::findOrFail($newChannelPlaylistId);

      if ($channel->playback_priority_type === 'channelPlaylist') {
        $selectedPlaylist->status = 'Active';
      } else {
        // Check if any other channels are using the playlist with 'channelPlaylist' priority type
        $isActive = Channel::where('channel_playlist_id', $newChannelPlaylistId)
            ->where('playback_priority_type', 'channelPlaylist')
            ->where('id', '!=', $channel->id)
            ->exists();

        $selectedPlaylist->status = $isActive ? 'Active' : 'Standby';
      }

      $selectedPlaylist->save();
    }

    // Update the channel's playlist ID
    $channel->channel_playlist_id = $newChannelPlaylistId;
    $channel->save();

    // Check and update the status of the previous playlist if it exists and was changed
    if ($currentChannelPlaylistId && $currentChannelPlaylistId != $newChannelPlaylistId) {
      $previousPlaylist = ChannelPlaylist::find($currentChannelPlaylistId);

      if ($previousPlaylist) {
        // Check if any other channels are using the previous playlist
        $channelsUsingPreviousPlaylist = Channel::where('channel_playlist_id', $currentChannelPlaylistId)
            ->where('id', '!=', $channel->id)
            ->get();

        if ($channelsUsingPreviousPlaylist->isEmpty()) {
          // No other channels are using this playlist
          $previousPlaylist->status = 'Inactive';
        } else {
          // Check if any other channels have the playback priority type set to 'channelPlaylist'
          $hasChannelPlaylistPriority = $channelsUsingPreviousPlaylist->contains(function ($channel) {
            return $channel->playback_priority_type === 'channelPlaylist';
          });

          if ($hasChannelPlaylistPriority) {
            $previousPlaylist->status = 'Active';
          } else {
            // If other channels are using the playlist but none have 'channelPlaylist' priority
            $previousPlaylist->status = 'Standby';
          }
        }

        $previousPlaylist->save();
      }
    }

    // Return a JSON response
    return response()->json([
        'success' => true,
        'message' => 'Channel ' . $channel->id . ' Channel Playlist Successfully Changed',
        'channel' => $channel,
    ]);
  }





  public function setExternalSource(Channel $channel, HttpRequest $request): JsonResponse {
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

  public function toggleChannelActive(Channel $channel): JsonResponse {
    // Toggle the 'active' status
    $channel->active = !$channel->active;
    $channel->save();

    // Determine the message and status based on the new 'active' state
    if ($channel->active) {
      $message = 'Channel ' . $channel->id . ' has been activated.';
      $status = 'success';
    } else {
      $message = 'Channel ' . $channel->id . ' has been deactivated.';
      $status = 'warning';
    }

    // Return a JSON response with dynamic message and status
    return response()->json([
        'success' => true,
        'message' => $message,
        'status' => $status, // Include the status in the response
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
   * @param HttpRequest $request
   * @return JsonResponse
   * @throws ValidationException
   */
    public function store(HttpRequest $request): JsonResponse {
      $validator = Validator::make($request->all(), [
          'name' => 'unique:channels|required|string|max:255'
        ]);
      // Check if validation fails, including both the custom check and the standard validation rules
      if ($validator->fails()) {
        // Use the validator's failed validation response
        $message = $validator;
        $status = 'error';
        // Return a JSON response with dynamic message and status
        return response()->json([
            'success' => false,
            'message' => $message,
            'status' => $status, // Include the status in the response
        ]);
      }
      // Validation passes, extract validated data
      $validatedData = $validator->validated();
      $channel = new Channel;

      $channel->name = $validatedData['name'];

      $channel->save();

      $message = 'Channel ' . $channel->name . ' has been created.';
      $status = 'success';
      // Return a JSON response with dynamic message and status
      return response()->json([
          'success' => true,
          'message' => $message,
          'status' => $status, // Include the status in the response
      ]);

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
     * @param HttpRequest $request
     * @param  Channel $channel
     * @return JsonResponse
     */
    public function update(HttpRequest $request, Channel $channel)
    {
      $validator = Validator::make($request->all(), [
        // Assume $channel is the model instance being updated
          'name' => [
              'required',
              'string',
              'max:255',
              Rule::unique('channels')->ignore($channel->id),
          ],
      ]);
      // Check if validation fails, including both the custom check and the standard validation rules
      if ($validator->fails()) {
        // Use the validator's failed validation response
        $message = $validator;
        $status = 'error';
        // Return a JSON response with dynamic message and status
        return response()->json([
            'success' => false,
            'message' => $message,
            'status' => $status, // Include the status in the response
        ]);
      }
      // Validation passes, extract validated data
      $validatedData = $validator->validated();

      $channel->name = $validatedData['name'];
      $channel->save();

      $message = 'Channel ' . $channel->name . ' has been updated.';
      $status = 'success';
      // Return a JSON response with dynamic message and status
      return response()->json([
          'success' => true,
          'message' => $message,
          'status' => $status, // Include the status in the response
      ]);
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
