<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CurrentViewers;
use Illuminate\Support\Facades\Auth;
use App\Events\ViewerAdded;
use App\Events\ViewerRemoved;

class CurrentViewersController extends Controller
{
    function addCurrentViewer(Request $request): \Illuminate\Http\JsonResponse
    {
        // Search for the current user_id, and if exists in CurrentViewers table
        // update the row with the current channel_id.

        $currentViewers = CurrentViewers::firstOrNew(
            ['user_id' => $request->user_id],
            ['channel_id' => $request->channel_id]
        );
        event(new ViewerAdded($currentViewers));
        $currentViewers->save();
        return response()->json(['success'], 201);

    }

    function removeCurrentViewer(CurrentViewers $currentViewers): void
    {
        // Remove the user_id from the CurrentViewers table.
        $currentViewers = CurrentViewers::where('user_id', Auth::user()->id);
        event(new ViewerRemoved($currentViewers));
        $currentViewers->delete();

    }

    function getCurrentViewers(Request $request) {
        return CurrentViewers::where('channel_id', $request->channel_id)->count();
    }
}
