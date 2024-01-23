<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ImageService
{

  ////////////// STORE IMAGE
  //////////////////////////
  /// created on January 22, 2024 by tec21 and ChatGPT using our previous methods
  public function storeImage($imageUrl, $folder)
  {
    // Get the cloud folder from the database
    $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
    $folderPath = Carbon::now()->format('/Y/m')."/{$folder}";

    // Download the image
    $imageContents = file_get_contents($imageUrl);
    if (!$imageContents) {
      // Handle error or return null if image download fails
      return null;
    }

    // Determine the MIME type of the image
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->buffer($imageContents);

    // Map MIME types to file extensions
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
      // Add other MIME types as needed
    ];
    $extension = $extensions[$mime_type] ?? 'jpg';

    // Generate a unique filename
    $filename = Str::random(40) . '.' . $extension;

    // Store the file in your storage disk
    Storage::disk('spaces')->put($cloud_folder . $folderPath . '/' . $filename, $imageContents);

    // Create and save the image model
    $image = Image::create([
        'user_id' => auth()->user()->id,
        'name' => $filename,
        'extension' => $extension,
        'size' => strlen($imageContents),
        'folder' => $folderPath,
        'cloud_folder' => $cloud_folder,
        'storage_location' => 'do_spaces',
    ]);

    return $image->id;
  }
}
