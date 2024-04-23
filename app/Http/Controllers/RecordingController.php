<?php

namespace App\Http\Controllers;

use App\Factories\MistServerServiceFactory;
use App\Models\AppSetting;
use App\Models\Show;
use App\Services\MistServer\MistServerService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecordingController extends Controller
{
  protected MistServerService $recordingService;

  public function __construct() {
    // Assuming the factory is adjusted to instantiate specific services based on the server type or another parameter
    $this->recordingService = MistServerServiceFactory::make('recording');
  }

  public function startRecording(Request $request, Show $show): \Illuminate\Http\JsonResponse {
    Log::debug('2b start recording in RecordingController');
    $validated = $request->validate([
        'stream_name'    => 'required|string',
    ]);

    $streamName = $validated['stream_name'];

//    $userRecordingsPath = $settings->mist_server_settings['mist_server_user_recording_folder'] ?? null;

    // delete this:
//    $userRecordingsPath = config('paths.user_recordings_path'); // this is set in the .env and referenced in our config/paths.php

//    Log::debug('userRecordingsPath: ' . $userRecordingsPath);

    try {
      $userRecordingsPath = null;
      $settings = AppSetting::find(1);

      if (isset($settings->mist_server_settings['mist_server_user_recording_folder']) && isset($user->id)) {
        $userRecordingsPath = rtrim($settings->mist_server_settings['mist_server_user_recording_folder'], '/') . '/' . $user->id . '/';
      }

      $fullPushUri = $userRecordingsPath . $streamName . '_' . Carbon::now()->format('Y.m.d.H.i.s') . '.mkv';
      Log::debug('fullPushUri', (array) $fullPushUri);
      $data = [
          $streamName,
          $fullPushUri,
      ];

//      Log::debug('start recording .', ['data' => $data]);
      $isRecordingStarted = $this->recordingService->startRecording($data);
      Log::debug('3 recording started back to RecordingController');
      Log::alert('User started recording for show: ' . $show->name);

      if (!$isRecordingStarted) {
        // Log the failure or handle it as needed
        Log::error('Failed to start recording', ['streamName' => $streamName]);
        return response()->json(['error' => 'Failed to start recording.'], 500);
      }

      // Proceed only if recording started successfully

      if ($show->mistStreamWildcard()->exists()) {
        Log::debug('4 mistStreamWildcard exists');
        $show->mistStreamWildcard()->update(['is_recording' => 1]);
        Log::debug('4 mistStreamWildcard is_recording set to true');
      } else {
        // Handle the case where $show does not have a related MistStreamWildcard
        Log::warning('No related MistStreamWildcard found for show.', ['showId' => $show->id]);
        return response()->json([
            'success' => false,
              'status' => 'error',
            'message' => 'No related MistStreamWildcard found for show ' . $show->name,
        ]);
      }
        // Dispatch the MistStreamPushStartJob with the destination model
        // Assuming dispatching the job is done here or somewhere else as needed
      Log::debug('5 returning to GoLiveStore');
        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => 'Recording started for ' . $show->name,
        ]);

    } catch (Exception $e) {
      Log::error('Exception caught in startRecording', [
          'message' => $e->getMessage(),
          'exception' => $e,
      ]);
      return response()->json(['error' => 'An error occurred while attempting to start recording.'], 500);
    }
  }

  public function stopRecording(Show $show, Request $request): JsonResponse {
    try {
    $validated = $request->validate([
        'stream_name'    => 'required|string',
    ]);

    $streamName = $validated['stream_name'];

    if (!$this->recordingService->stopRecording($streamName)) {
      Log::error('Failed to stop recording for show: ', ['streamName' => $streamName, 'showName' => $show->name]);
      return response()->json(['error' => 'Failed to stop recording.'], 500);
    }

    Log::alert('User stopped recording for show: ' . $show->name);
    return response()->json([
        'success' => true,
        'status' => 'info',
        'message' => 'Recording stopped for ' . $show->name,
    ]);
    } catch (Exception $e) {
      Log::error('Failed to stop recording from controller', ['show' => $show->name, 'message' => $e->getMessage()]);
      return response()->json(['error' => 'Failed to process your request'], 500);
    }

//    try {
//      $isRecordingStopped = $this->recordingService->stopRecording($streamName);
//      Log::alert('User stopped recording for show: ' . $show->name);
//
//      if (!$isRecordingStopped) {
//        // Log the failure or handle it as needed
//        Log::error('Failed to stop recording for show: ', ['streamName' => $streamName, 'showName' => $show->name]);
//        return response()->json(['error' => 'Failed to stop recording.'], 500);
//      }
//
//      // Proceed only if recording stopped successfully
//      $show->mistStreamWildcard()->update(['is_recording' => 0]);
//
//      return response()->json([
//          'success' => true,
//          'status' => 'info',
//          'message' => 'Recording stopped for ' . $show->name,
//      ]);
//
//    } catch (Exception $e) {
//      Log::error('Exception caught in stopPush', [
//          'showName' => $show->name,
//          'message' => $e->getMessage(),
//          'exception' => $e,
//      ]);
//      return response()->json(['error' => 'An error occurred while attempting to stop recording.'], 500);
//    }
  }
}
