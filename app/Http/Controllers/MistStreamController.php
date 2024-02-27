<?php

namespace App\Http\Controllers;

use App\Jobs\AddOrUpdateMistStreamJob;
use App\Jobs\RemoveMistStreamJob;
use App\Models\AppSetting;
use App\Models\Channel;
use App\Models\MistStream;
use App\Services\MistServerService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use RuntimeException;
use GuzzleHttp\Client;

class MistStreamController extends Controller {

  public function __construct() {

    $this->middleware('can:viewAny,' . MistStream::class)->only(['index']);
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

    Log::info('Raw Request', [
        'headers' => $request->headers->all(),
        'body'    => $request->getContent() // For raw body content
    ]);
//    return response('1', 200)
//        ->header('Content-Type', 'text/plain');
//
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

    $streamName = $lines[0] ?? 'unknown';
    // Assuming the IP address is always on the second line
    $ipAddress = $lines[1] ?? 'unknown'; // Default to 'unknown' if not found
    $requestUrl = $lines[4] ?? 'unknown'; // it's only line 5 if USER_NEW, otherwise its line 4.


//    Log::info($streamName);
    if ($streamName === 'test' || $streamName === 'first-play') {
      return response('1', 200);
    }

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
   * @return \Inertia\Response
   */
  public function searchMistStreams(Request $request) {
    $search = $request->input('search', '');

    // Directly fetch mistStreams based on the search input.
    $mistStreams = MistStream::query()
        ->when($search, function ($query, $search) {
          // Use the $search variable captured from the request
          return $query->where('name', 'like', "%{$search}%");
        })
        ->get();

    $filters = [
        'search' => $search, // Use the $search variable you've already set
    ];

    return Inertia::render('Admin/Channels/Index', [
        'mistStreams'              => $mistStreams, // Pass the fetched mistStreams directly
        'mistStreamsSearchFilters' => $filters,
    ]);

  }

  public function addOrUpdateMistStream(Request $request) {
    // Validate the incoming request
    $originalName = $request->input('originalName') ?? $request->input('name');

    $validatedData = $request->validate([
        'name' => [
            'required',
            'string',
            Rule::unique('mist_streams', 'name')->ignore($request->id),
            'regex:/^[a-z_\-\.]+[a-z0-9_\-\.]*$/',
        ],
        'source' => 'required|string',
        'mime_type' => 'required|string',
        'comment' => 'nullable|string',
        'metadata' => 'nullable|array' // Assuming metadata is sent as an associative array
    ], [
        'name.regex' => 'The name must be lowercase, cannot start with a number, and can only include . _ - characters.'
    ]);

    // Dispatch the job with validated data
    AddOrUpdateMistStreamJob::dispatch($validatedData, $originalName);

    // Return back with a success message
    return redirect()->back()->with('success', 'Mist Stream Successfully Added. Check the logs to confirm.');
  }

  public function removeMistStream(Request $request) {
    // Retrieve the MistStream by name
    $mistStreamName = $request->name;
    $mistStream = MistStream::where('name', $mistStreamName)->first();

    if (!$mistStream) {
      // MistStream not found, return with an error message
      return redirect()->back()->with('error', 'Mist Stream not found.');
    }

    // Check if the MistStream is used by any channels
    $relatedChannels = Channel::where('mist_stream_id', $mistStream->id)->get();

    if ($relatedChannels->isNotEmpty()) {
      // Build a comma-separated list of channel names (if the Channel model has a 'name' attribute)
      $channelNames = $relatedChannels->pluck('name')->join(', ');

      // Return back with a warning message
      return redirect()->back()->with('warning', "You cannot delete a MistStream that is currently on the following channel(s): {$channelNames}.");
    }

    // Dispatch job to remove MistStream from server
    RemoveMistStreamJob::dispatch($mistStream);

    // No related channels found, safe to delete
    $mistStream->delete();

    // Return back with a success message
    return redirect()->back()->with('message', 'Mist Stream Successfully Removed. Check the logs to confirm.');

  }

  public function fetchStreamInfo($streamName)
  {
    $encodedStreamName = urlencode($streamName);
    $mistServerIp = config('services.mistserver.internal_ip');
//    $url = "http://mist.nottv.io:8080/json_${encodedStreamName}.js"; // Replace with the actual URL
    $url = "http://${mistServerIp}:8080/json_${encodedStreamName}.js"; // Replace with the actual URL

    try {
      $response = Http::get($url);

      if ($response->successful()) {
        $streamInfo = $response->json();
        // Do something with $streamInfo
        return response()->json($streamInfo); // Example: Return the data as JSON response
      } else {
        throw new \Exception('Failed to fetch');
      }
    } catch (\Exception $e) {
      // Handle the error appropriately
      return response()->json(['error' => 'Error fetching stream info: ' . $e->getMessage()], 500);
    }
  }

  public function restoreAllStreams()
  {
    // Retrieve all Mist Streams from the database
    $mistStreams = MistStream::all();

    foreach ($mistStreams as $stream) {
      // Prepare the data for dispatching the job
      $streamData = [
          'name' => $stream->name,
          'source' => $stream->source,
          'mime_type' => $stream->mime_type,
          'comment' => $stream->comment ?? '',
          'metadata' => $stream->metadata ?? [], // Assuming 'metadata' is stored as an associative array and can be directly used
        // Add 'id' and 'originalName' if needed by the job for processing
          'id' => $stream->id,
          'originalName' => $stream->original_name ?? $stream->name,
      ];

      // Directly dispatch the job without going through the request validation
      try {
        AddOrUpdateMistStreamJob::dispatch($streamData, $stream->name);
      } catch (\Exception $e) {
        Log::error('Failed to restore Mist Stream: ' . $stream->name, [
            'exception' => $e->getMessage(),
        ]);
        // Optionally, add more error handling here
      }
    }

    return response()->json(['message' => 'All Mist Streams have been attempted to restore. Check logs for any errors.']);
  }


  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(MistServerService $mistServerService) {
    // Fetch streams
//    $streams = $mistServerService->fetchConfiguredStreams();
//    dd($streams);

    // Now retrieve the updated list from the database
    $mistStreams = MistStream::all();

    return response()->json($mistStreams);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create() {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return Response
   */
  public function store(Request $request) {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param \App\Models\MistStream $mistStream
   * @return Response
   */
  public function show(MistStream $mistStream) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param \App\Models\MistStream $mistStream
   * @return Response
   */
  public function edit(MistStream $mistStream) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param \App\Models\MistStream $mistStream
   * @return Response
   */
  public function update(Request $request, MistStream $mistStream) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Models\MistStream $mistStream
   * @return Response
   */
  public function destroy(MistStream $mistStream) {
    //
  }
}
