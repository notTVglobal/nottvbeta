<?php

namespace App\Http\Controllers\Api;

use App\Events\ViewerJoinChannel;
use App\Events\ViewerLeaveChannel;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use App\Models\CurrentViewers;
use Illuminate\Support\Facades\Auth;

class CurrentViewersController extends Controller
{

    public function channels(HttpRequest $request): \Illuminate\Database\Eloquent\Collection
    {
        return Channel::all();
    }

    function addCurrentViewer(HttpRequest $request, User $user): \Illuminate\Http\JsonResponse
    {
        event(new ViewerJoinChannel($request));
        // Search for the current user_id, and if exists in CurrentViewers table
        // update the row with the current channel_id.
        if (!$request->filled('channel_id')) {
            return response()->json([
                'channel_id' => 'No channel selected!'
            ], 422);
        }
        $alreadyViewing = CurrentViewers::where('user_id', '=', $request->user_id)->first();
        if ($alreadyViewing !== null) {
//            event(new ViewerJoinChannel($alreadyViewing));
//            UpdateCurrentViewersCount::dispatch($alreadyViewing);
            $alreadyViewing->update([$request->channel_id]);
        } else {
            $currentViewers = new CurrentViewers;
            $currentViewers->user_id = $request->user_id;
            $currentViewers->channel_id = $request->channel_id;

//            UpdateCurrentViewersCount::dispatch($currentViewers);
            $currentViewers->save();
        }
//        $currentViewers = CurrentViewers::where('channel_id', '=', $request->channel_id)->get();
//        $currentViewersCount = [
//            'viewer_count' => $currentViewers->count(),
//            'channel_id' => $request->channel_id,
//        ];
//        Broadcast(new UpdateCurrentViewersCount($currentViewersCount));

        return response()->json(['success'], 201);
    }

    function removeCurrentViewer(HttpRequest $request): \Illuminate\Http\JsonResponse
    {

        // Remove the user_id from the CurrentViewers table.
        if ($request->old_logged_out_id !== null){
            $oldViewer = CurrentViewers::where('user_id', '=', $request->old_logged_out_id)->firstOrFail();
            $oldViewer?->delete();
            event(new ViewerLeaveChannel($oldViewer));
        }
        if ($request->channel_id !==null){
            $currentViewers = CurrentViewers::where('user_id', '=', $request->user_id)->first();
            $currentViewers->delete();
            event(new ViewerLeaveChannel($currentViewers));
            return response()->json(['success'], 201);
        }
        $currentViewers = CurrentViewers::where('channel_id', '=', $request->channel_id)->get();
        $currentViewersCount = [
            'viewer_count' => $currentViewers->count(),
            'channel_id' => $request->channel_id,
        ];
        event(new ViewerLeaveChannel($currentViewers));
//        UpdateCurrentViewersCount::dispatch($currentViewersCount);
        return response()->json(['no channel'], 304);

    }

    function getCurrentViewers(HttpRequest $request): \Illuminate\Http\JsonResponse
    {
        $currentViewers = CurrentViewers::where('channel_id', $request->channel_id)->count();
        return response()->json([$currentViewers], 201);
    }
}
