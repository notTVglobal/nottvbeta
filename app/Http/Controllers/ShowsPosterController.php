<?php

namespace App\Http\Controllers;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function uploadPoster(HttpRequest $request)
    {
            // Store image locally
//        $request->file('poster')->store('images');

        // Store image on DO_SPACES
        if ($request->hasFile('poster')) {

            $user = auth()->user()->id;
            $showId = auth()->user()->isEditingShow_id;
            $uploadedFile = $request->file('poster');
            $folder = config('filesystems.disks.spaces.folder');
            $cdn = env('DO_SPACES_CDN_ENDPOINT');

            // create image model
            $id = DB::table('images')->insertGetId([
                'name' => $uploadedFile->hashName(),
                'extension' => $uploadedFile->extension(),
                'folder' => $folder,
                'cdn_endpoint' => $cdn,
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

            Storage::disk('spaces')->put(
                "{$folder}/{$poster}",
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




            // NEED TO PROTECT THESE




            // return that image model back to the frontend
            $poster = DB::table('images')->where('id', $id)->pluck('name')->first();

            return $poster;

        } return response()->json(['error' => 'The file could not be saved.'], 500);








//        Storage::disk('spaces')->put('example2.txt', 'Contents');

//        $file = $request->hasFile('poster');
//        $fileName = $this->createFilename($request->file('poster'));
//        $folder = config('filesystems.disks.spaces.folder');
//
//        Storage::disk('spaces')->put(
//            "{$folder}/{$filename}",
//            file_get_contents($request->file('poster'))
//        );
//        return response()->json(['message' => 'File uploaded'], 200);

//        $files = Storage::disk('spaces')->files('uploads');
//        function() {
//            Storage::disk('spaces')->putFile('uploads', request()->file, 'public');
//        };
//
//        if (!$path) {
//            return response()->json(['error' => 'The file could not be saved.'], 500);
//        }



        // tec21: this was my first way of doing this.
        // now we just change the image id on the show table
        // the images table can keep the show id for any image
        // uploaded to a show.
        //
        // remove previous image from show
//        DB::table('images')->where('show_id', $showId)->update([
//                    'show_id' => null,
//                ]);








//        return Inertia::render('Shows/{$id}/Edit');

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
