<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\MistStream;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MistStreamController extends Controller
{


  public function mistServerGet()
  {
    // use this to GET and POST data to and from MistServer.
  }

  public function mistServerPost()
  {
    // use this to GET and POST data to and from MistServer.
  }

  /**
   * Validates a video request to ensure it meets criteria for viewing.
   *
   * @param Request $request
   * @return Application|ResponseFactory|Response
   */
  public function validateUser(Request $request) {

    // Log raw request data
    Log::info('Raw Request', [
        'headers' => $request->headers->all(),
        'body' => $request->getContent() // For raw body content
    ]);

//    $secretKey = AppSetting::where('id', 1)->first()->mist_access_control_secret ?? 'fallback_secret';
//    return response()->json(1);
//          return response()->json('true');
//    return response('1', 200)->header('X-Trigger', 'USER_NEW');


    // Retrieve the secret key from configuration or fallback
    $secretKey = AppSetting::where('id', 1)->first()->mist_access_control_secret ?? 'default_secret';


    // Parsing the request body
    $bodyContent = $request->getContent();
    // Split the string by line breaks to get an array of lines
    $lines = explode("\n", $bodyContent);
    // Assuming the IP address is always on the second line
    $ipAddress = $lines[1] ?? 'unknown'; // Default to 'unknown' if not found

    $parts = explode(" ", $bodyContent);
    $requestUrl = end($parts); // Assuming the URL is the last part of the body
    parse_str(parse_url($requestUrl, PHP_URL_QUERY), $requestUrlParams);


    // Extracting userId and received hash from URL parameters
    $userId = $requestUrlParams['user'] ?? null;
    $hashReceived = $requestUrlParams['hash'] ?? null;

//    // Adjusting hash generation based on environment
//    if (App::environment('production')) {
//      // Include IP in hash for production
//
//    } else {
//      // Exclude IP in hash for development, just use userId and secret
//      $hashExpected = hash('sha256', $userId . $secretKey);
//    }

    $userIp = $request->ip();
    $hashExpected = hash('sha256', $userIp . $secretKey);

    Log::alert('Hash Debug', [
        'Expected Hash' => $hashExpected,
        'Received Hash' => $hashReceived,
        'Hash Base String (Expected)' => $userIp . $secretKey,
        'User ID' => $userId,
        'Request IP Address' => $userIp,
        'IP Address from Mist' => $ipAddress,
        'Secret Key' => $secretKey,
    ]);

    // Comparing the expected hash with the received hash
    if ($hashReceived !== $hashExpected) {
      Log::info('Unauthorized - Hash mismatch');
      return response('Unauthorized', 401); // Respond with 401 Unauthorized for invalid hash
    }


    // If hash matches, proceed with additional checks (if any) and ultimately approve access
    Log::info('Access granted');
    return response('1', 200); // Positive response for blocking triggers

//
//
//    // Parsing the request body
//    $bodyContent = $request->getContent();
//    $parts = explode(" ", $bodyContent);
//    $requestUrl = end($parts); // Assuming the URL is the last part of the body
//    parse_str(parse_url($requestUrl, PHP_URL_QUERY), $requestUrlParams);
//
//
//    // Check for allowed triggers from the header
//    $allowedTriggers = ["USER_NEW", "CONN_OPEN", "CONN_CLOSE", "CONN_PLAY"];
//    if (!in_array($request->header('X-Trigger'), $allowedTriggers)) {
//      error_log("This script is not compatible with triggers other than USER_NEW, CONN_OPEN, CONN_CLOSE, and CONN_PLAY");
//      Log::info('invalid_trigger');
//      return response('Invalid trigger', 400); // Use a 400 Bad Request response for invalid triggers
//    }
//
//    // Parsing the body assuming it's plain text
//    $bodyContent = $request->getContent();
//    $parts = explode(" ", $bodyContent);
//    $requestUrl = end($parts); // Assuming the URL is the last part of the body
//    parse_str(parse_url($requestUrl, PHP_URL_QUERY), $requestUrlParams);
//
//    // Extracting user and hash from the URL parameters
//    $userId = $requestUrlParams['user'] ?? null;
//    $hashReceived = $requestUrlParams['hash'] ?? null;
//
//    // Generating the hash, excluding IP in development
//    $userIp = App::environment('production') ? $request->ip() : '';
//    $hashExpected = md5($userIp . $userId . $secretKey);
//
//    if ($hashReceived !== $hashExpected) {
//      Log::info('Unauthorized - Hash mismatch');
//      return response('Unauthorized', 401);
//    }
//
//    // Your logic to check if the user is allowed to watch
//    Log::info('Access granted');
//    // Assuming userCanWatch returns true or false
//    return response()->json(['can_watch' => $this->userCanWatch($userId)]);







    // Assume get_payload() is a method to fetch payload; we replace it with Laravel's request data
    // This assumes payload is sent as JSON in the request body
//    $payload = $request->all();
//
//    // Always allow HTTP(S) requests
//    if (in_array($payload['protocol'] ?? '', ['HTTP', 'HTTPS'])) {
//      Log::info('Allowed');
//      return response('Allowed', 200);
//    }
//
//    // Validate the presence of necessary parameters
//    if (!isset($payload['request_url'])) {
//      error_log("Payload did not include request_url.");
//      Log::error('Missing request_url');
//      return response('Missing request_url', 400);
//    }
//
//    // Retrieve and parse the request URL parameters
//    $requestUrlParams = explode("?", $payload['request_url']);
//    parse_str($requestUrlParams[1] ?? '', $requestUrlParams);
//
//    if (!isset($payload['ip']) || !isset($requestUrlParams['hash']) || !isset($requestUrlParams['user'])) {
//      error_log("Payload missing required information (IP, hash, or user).");
//      Log::info('Invalid payload');
//      return response('Invalid payload', 400);
//    }
//
//    // Validate the hash/ip/userid combo (Assuming no_ffffs is a function to manipulate the IP)
//    if ($requestUrlParams['hash'] !== md5($this->noFfffs($payload['ip']) . $requestUrlParams['user'] . $secretKey)) {
//      Log::info('Unauthorized');
//      return response('Unauthorized', 401); // Respond with 401 Unauthorized for invalid hash
//    }
//
//    // Check if the user is allowed to watch the stream
//    Log::info('Other');
//    // Assuming userCanWatch returns a boolean
//    $canWatch = $this->userCanWatch($userId);
//
//    // Constructing the response based on the canWatch value
//    if ($canWatch) {
//      // If the user can watch, return '1' as the first part of the response
//      return response('1', 200);
//    } else {
//      // If the user cannot watch, return a negative response
//      // Depending on MistServer's handling, you might use '0', 'no', or another appropriate negative response
//      return response('0', 403); // Using 403 Forbidden status code as an example
//    }
//    return response()->json(1);
//    return response($approveOrDeny);
  }

  /**
   * Checks if a user can watch the stream.
   *
   * @param int $userId
   * @return bool
   */
  protected function userCanWatch($userId)
  {
    $canWatch = [1, 2, 3, 6];
    return in_array($userId, $canWatch);
  }

  /**
   * Example function to modify the IP address, if needed.
   *
   * @param string $ip
   * @return string
   */
  protected function noFfffs($ip)
  {
    // Manipulate the IP address as needed
    return $ip; // Placeholder: implement the actual logic
  }





  /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
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
