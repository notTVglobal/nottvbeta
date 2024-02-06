<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\MistStream;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
   */
  public function userValidation(Request $request): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response {
    Log::info($request);
    $secretKey = AppSetting::where('id', 1)->first()->mist_access_control_secret ?? 'fallback_secret';
//    return response()->json(1);
//          return response()->json('true');
//    return response('1', 200)->header('X-Trigger', 'USER_NEW');

    // Check for allowed triggers from the header
    $allowedTriggers = ["USER_NEW", "CONN_OPEN", "CONN_CLOSE", "CONN_PLAY"];
    if (!in_array($request->header('X-Trigger'), $allowedTriggers)) {
      error_log("This script is not compatible with triggers other than USER_NEW, CONN_OPEN, CONN_CLOSE, and CONN_PLAY");
      Log::info('invalid_trigger');
      return response('Invalid trigger', 400); // Use a 400 Bad Request response for invalid triggers
    }

    // Assume get_payload() is a method to fetch payload; we replace it with Laravel's request data
    // This assumes payload is sent as JSON in the request body
    $payload = $request->all();

    // Always allow HTTP(S) requests
    if (in_array($payload['protocol'] ?? '', ['HTTP', 'HTTPS'])) {
      Log::info('Allowed');
      return response('Allowed', 200);
    }

    // Validate the presence of necessary parameters
    if (!isset($payload['request_url'])) {
      error_log("Payload did not include request_url.");
      Log::info('Missing request_url');
      return response('Missing request_url', 400);
    }

    // Retrieve and parse the request URL parameters
    $requestUrlParams = explode("?", $payload['request_url']);
    parse_str($requestUrlParams[1] ?? '', $requestUrlParams);

    if (!isset($payload['ip']) || !isset($requestUrlParams['hash']) || !isset($requestUrlParams['user'])) {
      error_log("Payload missing required information (IP, hash, or user).");
      Log::info('Invalid payload');
      return response('Invalid payload', 400);
    }

    // Validate the hash/ip/userid combo (Assuming no_ffffs is a function to manipulate the IP)
    if ($requestUrlParams['hash'] !== md5($this->noFfffs($payload['ip']) . $requestUrlParams['user'] . $secretKey)) {
      Log::info('Unauthorized');
      return response('Unauthorized', 401); // Respond with 401 Unauthorized for invalid hash
    }

    // Check if the user is allowed to watch the stream
    Log::info('Other');
    return response()->json(['can_watch' => $this->userCanWatch($requestUrlParams['user'])]);
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
