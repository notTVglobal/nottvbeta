<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\MistStream;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MistStreamController extends Controller
{

  public function __construct() {

    $this->middleware('can:viewAny,' . \App\Models\MistStream::class)->only(['index']);
    $this->middleware('can:view,mistStream')->only(['show']);
    $this->middleware('can:create,' . MistStream::class)->only(['create']);
    $this->middleware('can:store,' . MistStream::class)->only(['store']);
    $this->middleware('can:edit,mistStream')->only(['edit']);
    $this->middleware('can:update,mistStream')->only(['update']);
    $this->middleware('can:delete,mistStream')->only(['delete']);
    $this->middleware('can:restore,mistStream')->only(['restore']);
    $this->middleware('can:forceDelete,mistStream')->only(['forceDelete']);
  }

  /**
   * Validates a video request to ensure it meets criteria for viewing.
   *
   * @param Request $request
   * @return Application|ResponseFactory|Response
   */
  public function validateUser(Request $request) {
    // NOTE: This currently uses the USER_NEW trigger.
    // The request_url is on a different line of the
    // request body in CONN_PLAY. And CONN_PLAY makes
    // more requests to the endpoint than USER_NEW.
    // It sends a request with every packet played.
    // Whereas USER_NEW sends a request to start a
    // new session and caches it on the MistServer.

    // Check for allowed triggers from the header
    $allowedTriggers = ["USER_NEW", "CONN_OPEN", "CONN_CLOSE", "CONN_PLAY"];
    if (!in_array($request->header('X-Trigger'), $allowedTriggers)) {
      error_log("This script is not compatible with triggers other than USER_NEW, CONN_OPEN, CONN_CLOSE, and CONN_PLAY");
      Log::info('invalid_trigger');
      //     Log raw request data for testing only
//      Log::info('Raw Request', [
//          'headers' => $request->headers->all(),
//          'body' => $request->getContent() // For raw body content
//      ]);
      return response('Invalid trigger', 400); // Use a 400 Bad Request response for invalid triggers
    }

    // Retrieve the secret key from configuration or fallback
    $secretKey = AppSetting::where('id', 1)->first()->mist_access_control_secret ?? 'default_secret';

    // Parsing the request body
    $bodyContent = $request->getContent();
    // Split the string by line breaks to get an array of lines
    $lines = explode("\n", $bodyContent);
    // Assuming the IP address is always on the second line
    $ipAddress = $lines[1] ?? 'unknown'; // Default to 'unknown' if not found
    $requestUrl = $lines[4] ?? 'unknown'; // it's only line 5 if USER_NEW, otherwise its line 4.

    parse_str(parse_url($requestUrl, PHP_URL_QUERY), $requestUrlParams);

    // Extracting userId and received hash from URL parameters
    $userId = $requestUrlParams['user'] ?? null;
    if (!$userId) {
      return response('User ID is required', 400);
    }
    $hashReceived = $requestUrlParams['hash'] ?? null;
    $hashExpected = hash('sha256', $userId . $ipAddress . $secretKey);

    // Comparing the expected hash with the received hash
    if ($hashReceived !== $hashExpected) {
      Log::warning("Hash mismatch for user ID: {$userId}");
      return response('Unauthorized - Hash mismatch', 401);  // Respond with 401 Unauthorized for invalid hash
    }

//    Log::alert('Hash Debug', [
//        'Expected Hash' => $hashExpected,
//        'Received Hash' => $hashReceived,
//        'Hash Base String (Expected)' => $userId . $ipAddress . $secretKey,
//        'User ID' => $userId,
//        'IP Address from Mist' => $ipAddress,
//        'Secret Key' => $secretKey,
//    ]);

    // If hash matches, proceed with additional checks (if any) and ultimately approve access
    Log::info('Access granted');
    return response('1', 200); // Positive response for blocking triggers

  }


  /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
//        return MistStream::class
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MistStream  $mistStream
     * @return Response
     */
    public function show(MistStream $mistStream)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MistStream  $mistStream
     * @return Response
     */
    public function edit(MistStream $mistStream)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MistStream  $mistStream
     * @return Response
     */
    public function update(Request $request, MistStream $mistStream)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MistStream  $mistStream
     * @return Response
     */
    public function destroy(MistStream $mistStream)
    {
        //
    }
}
