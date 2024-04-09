<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeamsLogoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function uploadLogo(HttpRequest $request)
    {
        // Store image locally
//        $request->file('logo')->store('images');

        // Store image on DO_SPACES
        if ($request->hasFile('logo')) {

            // Need to validate/sanitize the image upload.
            // strip the metadata and convert the image file.
            // don't save the original! HACKER WARNING!!

            $user = auth()->user()->id;
            $teamId = auth()->user()->isEditingTeam_id;
            $uploadedFile = $request->file('logo');
            $folder = config('filesystems.disks.spaces.folder');

            // create image model
            // 1. remove previous image from show
            DB::table('images')->where('team_id', Auth::user()->isEditingTeam_id)->update([
                'team_id' => null,
            ]);

            // 2. update image model with new image
            $id = DB::table('images')->insertGetId([
                'name'      => $uploadedFile->hashName(),
                'extension' => $uploadedFile->extension(),
                'folder'    => $folder,
                'size'      => $uploadedFile->getSize(),
                'user_id'   => $user,
                'team_id'   => Auth::user()->isEditingTeam_id,
            ]);

            // store image_id to Shows table
            $team = Team::find($teamId);
            $team->image_id = $id;
            $team->save();

            // update Image on frontend
            // return that image model back to the frontend
            $logo = DB::table('images')->where('id', $id)->first();

            Storage::disk('spaces')->put(
                "{$folder}/{$logo->name}",
                file_get_contents($request->file('logo'))
            );

            return $logo;
//        return Inertia::render('Shows/{$id}/Edit');
        }
    }
}
