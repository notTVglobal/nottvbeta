<?php

namespace App\Http\Controllers;

use App\Factories\MistServerServiceFactory;
use App\Jobs\AddOrUpdateMistStreamJob;
use App\Jobs\RemoveMistStreamJob;
use App\Models\Channel;
use App\Models\MistStream;
use App\Services\MistServer\MistServerService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class MistStreamController extends Controller {

  private MistServerService $pushService;

  public function __construct() {

    $this->pushService = MistServerServiceFactory::make('push');

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
        'name'      => [
            'required',
            'string',
            Rule::unique('mist_streams', 'name')->ignore($request->id),
            'regex:/^[a-z_\-\.]+[a-z0-9_\-\.]*$/',
        ],
        'source'    => 'required|string',
        'mime_type' => 'required|string',
        'comment'   => 'nullable|string',
        'metadata'  => 'nullable|array' // Assuming metadata is sent as an associative array
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

  public function fetchStreamInfo(Request $request): \Illuminate\Http\JsonResponse {

    // Validate the request data
    $validated = $request->validate([
        'streamName'    => 'required|string',
        'mistServerUri' => 'required|string',
    ]);

    $streamName = $validated['streamName'];

    return $this->pushService->fetchStreamInfo($streamName);
  }



  public function restoreAllStreams() {
    // Retrieve all Mist Streams from the database
    $mistStreams = MistStream::all();

    foreach ($mistStreams as $stream) {
      // Prepare the data for dispatching the job
      $streamData = [
          'name'         => $stream->name,
          'source'       => $stream->source,
          'mime_type'    => $stream->mime_type,
          'comment'      => $stream->comment ?? '',
          'metadata'     => $stream->metadata ?? [], // Assuming 'metadata' is stored as an associative array and can be directly used
        // Add 'id' and 'originalName' if needed by the job for processing
          'id'           => $stream->id,
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
