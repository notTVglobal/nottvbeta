<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{

    public function index()
    {
        $images = Image::latest()->get();
        return Inertia::render('Image', ['images' => $images]);
    }

    public function show()
    {
        // return all images
        return Image::latest()->pluck('name')->toArray();
    }

    public function store(Request $request)
    {
        // validate the incoming file
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'There is no image present'], 400);
        }

        $request->validate([
            'image' => 'required|file|image'
        ]);

        // save the file in storage
        $path = $request->file('image')->store('images');
//        $files = Storage::disk('spaces')->files('uploads');
//        function() {
//            Storage::disk('spaces')->putFile('uploads', request()->file, 'public');
//        };

        if (!$path) {
            return response()->json(['error' => 'The file could not be saved.'], 500);
        }

        $uploadedFile = $request->file('image');

        // create image model
        // NEED TO PROTECT THESE
        $image = Image::create([
            'name' => $uploadedFile->hashName(),
            'extension' => $uploadedFile->extension(),
            'size' => $uploadedFile->getSize()
        ]);

        // return that image model back to the frontend
        return $image->name;

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




    }


}
