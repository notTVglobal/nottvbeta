<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use App\Models\CurrentViewers;
use Illuminate\Support\Facades\Auth;
use App\Events\ViewerCountIncrement;
use App\Events\ViewerCountDecrement;

class CurrentViewersController extends Controller
{
    function addCurrentViewer(HttpRequest $request, User $user): \Illuminate\Http\JsonResponse
    {
        // Search for the current user_id, and if exists in CurrentViewers table
        // update the row with the current channel_id.
        if (!$request->filled('channel_id')) {
            return response()->json([
                'channel_id' => 'No channel selected!'
            ], 422);
        }
        $alreadyViewing = CurrentViewers::where('user_id', $request->user_id)->first();
        if ($alreadyViewing !== null) {
            $alreadyViewing->update([$request->channel_id]);
            event(new ViewerCountIncrement($alreadyViewing));
        } else {
            $currentViewers = new CurrentViewers;
            $currentViewers->user_id = $request->user_id;
            $currentViewers->channel_id = $request->channel_id;
            event(new ViewerCountIncrement($currentViewers));
            $currentViewers->save();
        }
        return response()->json(['success'], 201);

    }

    function removeCurrentViewer(HttpRequest $request): \Illuminate\Http\JsonResponse
    {
        // Remove the user_id from the CurrentViewers table.
        if ($request->old_logged_out_id !== null){
            $oldViewer = CurrentViewers::where('user_id', $request->old_logged_out_id)->firstOrFail();
            $oldViewer?->delete();
        }
        if ($request->channel_id !==null){
            $currentViewers = CurrentViewers::where('user_id', $request->user_id)->first();
            event(new ViewerCountDecrement($currentViewers));
            $currentViewers->delete();
            return response()->json(['success'], 201);
        }
        return response()->json(['no channel'], 304);

    }

    function getCurrentViewers(HttpRequest $request): \Illuminate\Http\JsonResponse
    {
        $currentViewers = CurrentViewers::where('channel_id', $request->channel_id)->count();
        return response()->json([$currentViewers], 201);
    }
}
