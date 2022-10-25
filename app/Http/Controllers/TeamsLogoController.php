<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamsLogoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function uploadLogo(HttpRequest $request) {
        $request->file('logo')->store('images');

////        $files = Storage::disk('spaces')->files('uploads');
////        function() {
////            Storage::disk('spaces')->putFile('uploads', request()->file, 'public');
////        };
//
//        if (!$path) {
//            return response()->json(['error' => 'The file could not be saved.'], 500);
//        }
        $user = auth()->user()->id;
        $uploadedFile = $request->file('logo');
        // create image model
        // NEED TO PROTECT THESE
//        $image = Image::create([
//            'name' => $poster,
//            'extension' => $uploadedFile->extension(),
//            'size' => $uploadedFile->getSize(),
//            'user_id' => $user,
//        ]);

        // create image model
        // remove previous image from show
        DB::table('images')->where('team_id', Auth::user()->isEditingTeam_id)->update([
            'team_id' => null,
        ]);

        // update image model with new image
        $id = DB::table('images')->insertGetId([
            'name'      => $uploadedFile->hashName(),
            'extension' => $uploadedFile->extension(),
            'size'      => $uploadedFile->getSize(),
            'user_id'   => $user,
            'team_id'   => Auth::user()->isEditingTeam_id,
        ]);

        // store image_id to Shows table

        // update Image on frontend
        // return that image model back to the frontend
        $logo = DB::table('images')->where('id', $id)->pluck('name')->first();

        return $logo;
//        return Inertia::render('Shows/{$id}/Edit');

    }
}
