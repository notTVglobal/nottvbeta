<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{

    public function index()
    {
        return Inertia::render('Image');
    }

    public function show()
    {
        // return all images
    }

    public function store(Request $request)
    {
        // validate the incoming file
        if (!$request->hasFile('imaage')) {
            return response()->json(['error' => 'There is no image present'], 400);
        }

        $request->validate([
            'image' => 'required|file|image'
        ]);

        // save the file in storage
        $path = $request->file('image')->store('public/images');
//        $files = Storage::disk('spaces')->files('uploads');
//        function() {
//            Storage::disk('spaces')->putFile('uploads', request()->file, 'public');
//        };

        if (!$path) {
            return response()->json(['error', 'The file could not be saved.'], 500);
        }

        $uploadedFile = $request->file('image');

        // create image model
        // NEED TO PROTECT THESE
        $image = Image::create([
            'name' => $uploadedFile->hashName(),
            'extension' => $uploadedFile->extension(),
            'size' => $uploadedFile->getSize()
        ]);


        //Route::get('/upload', function() {
//
//
//    return view('upload', compact('files'));
//});
//
//Route::post('upload',
//
//    return redirect()->back();
//});



        // return that image model back to the frontend
        return $image;
    }


}
