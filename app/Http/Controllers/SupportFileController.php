<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SupportFileController extends Controller
{
  /**
   * This controller handles requests to serve support files, such as JSON data files,
   * used by the application. It currently includes a method to retrieve timezones
   * from a predefined JSON file.
   */

  /**
   * Retrieve a list of timezones from a JSON file and return them as a JSON response.
   *
   * @return JsonResponse
   */
  public function getTimezones(): JsonResponse {
    $path = base_path('support_files/json_files/timezones.json');

    if (!File::exists($path)) {
      abort(404);
    }

    $file = File::get($path);
    $data = json_decode($file, true);

    return response()->json($data);
  }
}
