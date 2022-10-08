<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
//use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{

    public function index() {
//        $images = Image::latest()->get();
//        $images = Image::simplePaginate(10);
//
//
//        return Inertia::render('Image', [
//            'images' => $images
//        ]);
////
        // tec21: this search functionality only works with Illuminate\Support\Facades\Request
        // which ends up breaking the filepond upload function which uses Illuminate\Http\Request
        return Inertia::render('Image', [
            'images' => Image::query()
//                ->when(Request::input('search'), function ($query, $search) {
//                    $query->where('name', 'like', "%{$search}%");
//                })
                ->latest()
                ->paginate(10)
                ->withQueryString()
                ->through(fn($image) => [
                    'id' => $image->id,
                    'name' => $image->name,
                    'extension' => $image->extension
                ]),
//            'filters' => Request::only(['search'])
        ]);
    }

    public function show()
    {
        // return all images
        return Image::latest()->pluck('name')->toArray();
    }

    public function store(Request $request)
    {
        // validate the incoming file
//        if (!$request->hasFile('image')) {
//            return response()->json(['error' => 'There is no image present'], 400);
//        }
//        $request->validate([
//            'image' => 'required|file|image|mimes:jpg,jpeg,png'
//        ]);
//
//        // save the file in storage
//        $path = $request->file('image')->store('images');
          $request->file('image')->store('images');

////        $files = Storage::disk('spaces')->files('uploads');
////        function() {
////            Storage::disk('spaces')->putFile('uploads', request()->file, 'public');
////        };
//
//        if (!$path) {
//            return response()->json(['error' => 'The file could not be saved.'], 500);
//        }
        $user_id = auth()->user()->id;
        $uploadedFile = $request->file('image');

        // create image model
        // NEED TO PROTECT THESE
        $image = Image::create([
            'name' => $uploadedFile->hashName(),
            'extension' => $uploadedFile->extension(),
            'size' => $uploadedFile->getSize(),
            'user_id' => $user_id,
        ]);

        // return that image model back to the frontend

        return $image;

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
