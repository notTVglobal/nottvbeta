<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use App\Models\ShowEpisode;

class ShowEpisodesPosterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function uploadPoster(HttpRequest $request)
    {
        $request->file('poster')->store('images');

////        $files = Storage::disk('spaces')->files('uploads');
////        function() {
////            Storage::disk('spaces')->putFile('uploads', request()->file, 'public');
////        };
//
//        if (!$path) {
//            return response()->json(['error' => 'The file could not be saved.'], 500);
//        }

        $user = auth()->user()->id;
        $showEpisodeId = auth()->user()->isEditingShowEpisode_id;
        $uploadedFile = $request->file('poster');

        // tec21: this was my first way of doing this.
        // now we just change the image id on the show table
        // the images table can keep the show id for any image
        // uploaded to a show.
        //
        // remove previous image from show
//        DB::table('images')->where('episode_id', $showEpisodeId)->update([
//                    'episode_id' => null,
//                ]);

        // NEED TO PROTECT THESE
        // create image model
        $id = DB::table('images')->insertGetId([
            'name' => $uploadedFile->hashName(),
            'extension' => $uploadedFile->extension(),
            'size' => $uploadedFile->getSize(),
            'user_id' => $user,
            'show_episode_id' => Auth::user()->isEditingShowEpisode_id,
        ]);

        // store image_id to Shows table
        $episode = ShowEpisode::find($showEpisodeId);
        $episode->image_id = $id;
        $episode->save();

        // return that image model back to the frontend
        $poster = DB::table('images')->where('id', $id)->pluck('name')->first();

        return $poster;
//        return Inertia::render('Shows/{$id}/Edit');

    }
}
