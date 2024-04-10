<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use function GuzzleHttp\Psr7\mimetype_from_filename;

class DigitalOceanSpacesController extends Controller
{

// $attribute_name: name of the model's attribute
// $disk: name of the disk configured before
// $destination_path: name of the folder you want to store the files in Digital Ocean Space

    public function setFileAttribute($value) {
      $attribute_name = "file";
      $disk = "do_spaces";
      $destination_path = "upload";
      $this->uploadFileToDisk($value, $attribute_name, $disk,
      $destination_path);
    }

    public function getFile($id){
        $document = Document::where('id','=', $id)->firstOrFail();
        $file = Storage::disk('do_spaces')->get($document->file);
        $mimetype = mimetype_from_filename($document->file);
        $headers = [
            'Content-Type' => $mimetype,
        ];
        return response($file, 200, $headers);
    }
}
