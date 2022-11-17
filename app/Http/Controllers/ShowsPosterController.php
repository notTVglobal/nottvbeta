<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Show;
use Illuminate\Support\Facades\Storage;

class ShowsPosterController extends Controller
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
        $showId = auth()->user()->isEditingShow_id;
        $uploadedFile = $request->file('poster');

        // tec21: this was my first way of doing this.
        // now we just change the image id on the show table
        // the images table can keep the show id for any image
        // uploaded to a show.
        //
        // remove previous image from show
//        DB::table('images')->where('show_id', $showId)->update([
//                    'show_id' => null,
//                ]);

        // NEED TO PROTECT THESE
        // create image model
        $id = DB::table('images')->insertGetId([
            'name' => $uploadedFile->hashName(),
            'extension' => $uploadedFile->extension(),
            'size' => $uploadedFile->getSize(),
            'user_id' => $user,
            'show_id' => Auth::user()->isEditingShow_id,
        ]);

        // store image_id to Shows table
        $show = Show::find($showId);
        $show->image_id = $id;
        $show->save();

        // return that image model back to the frontend
        $poster = DB::table('images')->where('id', $id)->pluck('name')->first();

        return $poster;
//        return Inertia::render('Shows/{$id}/Edit');

    }
}
