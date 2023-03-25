<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Show;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ShowsPosterController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param HttpRequest $request
     * @return Model|Builder|object
     */

    public function uploadPoster(HttpRequest $request)
    {
            // Store image locally
//        $request->file('poster')->store('images');

        // Store image on DO_SPACES
        if ($request->hasFile('poster')) {

            // Need to validate/sanitize the image upload.
            // strip the metadata and convert the image file.
            // don't save the original! HACKER WARNING!!

            $user = auth()->user()->id;
            $showId = auth()->user()->isEditingShow_id;
            $uploadedFile = $request->file('poster');
            $folder = config('filesystems.disks.spaces.folder');

            // create image model
            $id = DB::table('images')->insertGetId([
                'name' => $uploadedFile->hashName(),
                'extension' => $uploadedFile->extension(),
                'folder' => $folder,
                'size' => $uploadedFile->getSize(),
                'user_id' => $user,
                'show_id' => Auth::user()->isEditingShow_id,
            ]);

            // store image_id to Shows table
            $show = Show::find($showId);
            $show->image_id = $id;
            $show->save();

            // return that image model back to the frontend
            $poster = DB::table('images')->where('id', $id)->first();

            Storage::disk('spaces')->put(
                "{$folder}/{$poster->name}",
                file_get_contents($request->file('poster'))
            );

            return $poster;

//            $file = $request->file('poster');
//            $filename = $uploadedFile->getClientOriginalName();
//            $folder = config('filesystems.disks.spaces.folder');
//            $file->storeAs('folder/', $filename, 'spaces');
//            Storage::disk('spaces')->put(
//                "{$folder}/{$filename}",
//                file_get_contents($request->file('poster'))
//            );


        } return response()->json(['error' => 'The file could not be saved.'], 500);


    }


    protected function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace(".".$extension, "", $file->getClientOriginalName()); // Filename without extension

        // Add timestamp hash to name of the file
        $filename .= "_" . md5(time()) . "." . $extension;

        return $filename;
    }





}
