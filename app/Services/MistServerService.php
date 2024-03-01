<?php

namespace App\Services;

use App\Models\MistServerConfig;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MistServerService {
  protected $host;
  protected $username;
  protected $password;
  protected $challenge;

  public function __construct() {
//  Log::debug('Constructing MistServerService');
//  $this->host = config('services.mistserver.host');
//  Log::debug("MistServer host URL after constructor: " . $this->host);

    // Access configuration values
    $this->host = config('services.mistserver.host');
    $this->username = config('services.mistserver.username');
    $this->password = config('services.mistserver.password');
  }

  public function send(array $data = []) {
    Log::debug("Sending request to MistServer", ['url' => $this->host, 'data' => $data]);

    if ($this->challenge) {
      // If there is a challenge, hash the password with the challenge
      $hashedPassword = md5($this->password);
      $authReturn = md5($hashedPassword . $this->challenge);
    } else {
      // Initial request, no challenge yet
      $authReturn = md5($this->password);
    }

    $data = array_merge($data, [
        "authorize" => [
            "username" => $this->username,
            "password" => $authReturn,
        ],
        "minimal"   => true,
    ]);

    $response = Http::get($this->host, ['command' => json_encode($data)]);

    if ($response->failed()) {
      Log::error('Request to MistServer failed', [
          'status'   => $response->status(),
          'response' => $response->body(),
      ]);

      return ['error' => 'Request to MistServer failed'];
    }

    $responseData = $response->json();
    Log::debug("Received response from MistServer", ['response' => $responseData]);


    if (isset($responseData['authorize']) && $responseData['authorize']['status'] === 'CHALL') {
      $this->challenge = $responseData['authorize']['challenge'];

      Log::debug("Received challenge from MistServer, retrying", ['challenge' => $this->challenge]);

      return $this->send($data); // Retry with the challenge response
    }

    return $responseData; 

  }

  public function fetchConfiguredStreams() {

    // Given the critical note from the MistServer documentation
    // that sending an empty streams request will clear all
    // configured streams, it's essential to approach the fetch
    // and update operations with caution to avoid unintended
    // stream removals.
    //
    // It's crucial to ensure the send method's implementation
    // does not inadvertently send an empty streams object
    // when the intention is to fetch data.

    // This should be safe as per MistServer's documentation
    $response = $this->send(["streams" => "list"]); // Adjusted to specifically request a list

    if (isset($response['error'])) {
      Log::error("Error fetching configured streams from MistServer", ['error' => $response['error']]);

      return false;
    }

    Log::debug("Configured streams fetched successfully", ['streams' => $response['streams']]);

    return $response['streams']; // Assuming this contains the stream configurations
  }

  public function updateConfiguredStreams(array $streamsToUpdate) {

    // Key Point: Always ensure $streamsToUpdate or the method
    // used to generate this array does not produce an empty
    // set unless you intend to clear all streams (which is
    // rarely the case). The example above assumes you're
    // correctly handling the merge to maintain the integrity
    // of your stream configurations.

    // First, fetch the current stream configuration to avoid overwriting unintentionally
    $currentStreams = $this->fetchConfiguredStreams();
    if (!$currentStreams) {
      return false; // Handle error appropriately
    }

    // Prepare the updated streams configuration
    // This example assumes you're merging or replacing the fetched configuration with new data
    $updatedStreamsConfiguration = array_merge($currentStreams, $streamsToUpdate);

    // Now send the complete, updated configuration back to MistServer
    $response = $this->send(["streams" => $updatedStreamsConfiguration]);

    if (isset($response['error'])) {
      Log::error("Error updating configured streams on MistServer", ['error' => $response['error']]);

      return false;
    }

    Log::debug("Configured streams updated successfully", ['streams' => $response['streams']]);

    return $response['streams'];
  }


  public function addOrUpdateStream($streamName, array $streamDetails): bool {

//    Stream Details: The $streamDetails parameter should be an associative
// array containing all the necessary details for the stream you're adding
// or updating, similar to the push stream example with optional parameters
// provided in the MistServer documentation. This gets stored in the metadata
// json column on the MistStream table.

//    Optional Parameters:
//    {
//      "name": "livestream01",
//      "source": "push://localhost@password",
//      "stop_sessions": false,
//      "DVR": "120000",
//      "cut": null,
//      "debug": null,
//      "fallback_stream": null,
//      "maxkeepaway": null,
//      "resume": null,
//      "segmentsize": "6000",
//      "processes": []
//    }

//    Multiple Streams: Although this function is set up to handle adding or
// updating a single stream, it can be easily extended to handle multiple
// streams simultaneously by adjusting the $data array structure to include
// more streams under the "addstream" key.

//    Handling Responses: The function checks for an "incomplete list" key
// in the response to verify that the operation was successful and only
// updated/new streams are returned. This is in line with the documentation
// stating that the result will not contain all streams but only those updated
// or newly added.

//    Logging: Logging has been included for both successful operations and
// errors, ensuring you have visibility into the function's outcomes.

    Log::info("Adding or updating a stream on MistServer", ['streamName' => $streamName]);

    // Directly use $streamDetails without filtering out null values
    // Assuming null values are intentionally set for unsetting parameters

    $data = [
        "addstream" => [
            $streamName => $streamDetails // streamDetails aka optional parameters are stored in the metadata json column.
        ]
    ];


    $response = $this->send($data);

    if (isset($response['error'])) {
      Log::error("Error adding/updating stream on MistServer", ['error' => $response['error']]);

      return false;
    }

    if (isset($response["streams"]["incomplete list"])) {
      Log::info("Received incomplete list indication, stream added/updated", ['stream' => $response["streams"][$streamName]]);
    } else {
      Log::error("Unexpected response format from MistServer after adding/updating stream");
    }

    return true; // Indicate success
//    return $response["streams"][$streamName] ?? false; // Return the specific stream's update details or false if not present.
  }


  public function removeStream($streamNames): bool {

//    Flexible Input: The method is designed to accept both a single
// stream name and an array of stream names for deletion. This
// flexibility allows for easy integration with different use cases
// within your application.

//    Logging: Incorporates logging at various stages to ensure that
// actions and errors are recorded, facilitating troubleshooting and
// monitoring of stream removal operations.

//    Response Handling: Checks for the "incomplete list" indication
// in the response to verify that the operation was successful. Note
// that the actual list of streams is not returned in this scenario,
// based on the documentation's description.

    Log::info("Removing stream(s) from MistServer", ['streamNames' => $streamNames]);

    // Prepare the data for the "deletestream" call
    // Automatically handle both single stream name and array of stream names
    $data = [
        "deletestream" => is_array($streamNames) ? $streamNames : [$streamNames]
    ];

    $response = $this->send($data);

    if (isset($response['error'])) {
      Log::error("Error removing stream(s) from MistServer", ['error' => $response['error']]);

      return false;
    }

    // Check for the special "incomplete list" indication
    if (isset($response["streams"]["incomplete list"])) {
      Log::info("Stream(s) removed successfully, received incomplete list indication");
    } else {
      Log::error("Unexpected response format from MistServer after removing stream(s)");
    }

    return true; // Assuming successful removal if no errors were encountered
  }

  public function getPushAutoList(): array {
    $data = ["push_auto_list" => true]; // The value is ignored, so true is just a placeholder

    try {
      $response = $this->send($data); // Assuming 'send' method handles communication with MistServer
      if (isset($response['push_auto_list']) && is_array($response['push_auto_list'])) {
        Log::info("Successfully retrieved push auto list from MistServer.");

        return $response['push_auto_list'];
      } else {
        Log::error("Failed to retrieve push auto list. Response was not as expected.");

        return [];
      }
    } catch (\Exception $e) {
      Log::error("Exception occurred while fetching push auto list", ['exception' => $e->getMessage()]);

      return [];
    }
  }

  public function pushAutoAdd($destination): void {

    if (!$destination->relationLoaded('mistStreamWildcard')) {
      $destination->load('mistStreamWildcard');
    }

    // Correctly format the target URL and prepare the stream name
    $targetURL = $destination->rtmp_url . $destination->rtmp_key;
    $streamName = $destination->mistStreamWildcard->name;

    Log::warning('add ::::: ' . $targetURL . ' ::::: ' . $streamName);
    $data = [
        "push_auto_add" => [
            "stream" => $streamName,
            "target" => $targetURL,
//            "scheduletime" => '', // this can get data from the showSchedule but is a future project.
//            "completetime" => '', // this can get data from the showSchedule but is a future project.
        ]
    ];

    try {
      $this->send($data); // Assuming 'send' method handles communication with MistServer
      $destination->has_auto_push = 1;
      $destination->save();
      // Log success with more detail
      Log::info("Push auto add successful for stream: {$streamName} to target: {$targetURL}");
    } catch (\Exception $e) {
      // Log the error with detail
      Log::error("Failed to request push_auto_add for stream: {$streamName} to target: {$targetURL}", ['exception' => $e->getMessage()]);
      // Optionally, rethrow or handle the exception as needed
    }
  }

  public function pushAutoRemove($destination): void {

    if (!$destination->relationLoaded('mistStreamWildcard')) {
      $destination->load('mistStreamWildcard');
    }

    // Prepare the target URL and the stream name as done during the add
    $targetURL = $destination->rtmp_url . $destination->rtmp_key;
    $streamName = $destination->mistStreamWildcard->name;

    Log::warning('remove ::::: ' . $targetURL . ' ::::: ' . $streamName);
//    $data = [
//        $destination->mistStreamWildcard->name,
//        $targetURL,
//    ];
    $data = [
        "push_auto_remove" => [
            "stream" => $streamName,
            "target" => $targetURL,
//            "scheduletime" => '', // this can get data from the showSchedule but is a future project.
//            "completetime" => '', // this can get data from the showSchedule but is a future project.
        ]
    ];

    // Wrapping the data inside an array to match the expected format

    try {
      $this->send($data); // Assuming 'send' method handles communication with MistServer
//      $this->send($data); // Assuming 'send' method handles communication with MistServer
      // Since there's no response, consider success if no exception is thrown

      // Optionally update the destination to reflect the removal
      $destination->has_auto_push = 0;
      $destination->save();

      Log::info("Push auto remove successful for stream: {$streamName} to target: {$targetURL}");
    } catch (\Exception $e) {
      Log::error("Failed to request push_auto_remove for stream: {$streamName} to target: {$targetURL}", ['exception' => $e->getMessage()]);
    }
  }

  public function getActivePushList(): array {
    $data = ["push_list" => true]; // The value is ignored, so true is just a placeholder

    try {
      $response = $this->send($data); // Assuming 'send' method handles communication with MistServer
      if (isset($response['push_list']) && is_array($response['push_list'])) {
        Log::info("Successfully retrieved active push list from MistServer.");

        return $response['push_list'];
      } else {
        Log::error("Failed to retrieve active push list. Response was not as expected.");

        return [];
      }
    } catch (\Exception $e) {
      Log::error("Exception occurred while fetching active push list", ['exception' => $e->getMessage()]);

      return [];
    }
  }

  public function startPush($destination): void {
    $streamName = $destination->mistStreamWildcard->name; // Adjust based on your model
    $targetURL = $destination->rtmp_url . $destination->rtmp_key;

    $data = [
        "push_start" => [
            "stream" => $streamName,
            "target" => $targetURL,
        ]
    ];

    try {
      $this->send($data); // Send the request to MistServer
      $destination->push_is_started = 1;
      $destination->save();
      Log::info("Push start successful for stream: {$streamName} to target: {$targetURL}");
    } catch (\Exception $e) {
      Log::error("Failed to start push for stream: {$streamName} to target: {$targetURL}", ['exception' => $e->getMessage()]);
    }
  }

  public function stopPush($destination): void {
    // Fetch the list of active pushes from MistServer
    $activePushes = $this->getActivePushList();

    // Construct the first URI from the destination details
    $targetURI = $destination->rtmp_url . $destination->rtmp_key;
    $streamName = $destination->mistStreamWildcard->name;

    // Search for the push that matches the stream name and first URI
    $pushId = null;
    foreach ($activePushes as $push) {
      // Assuming $push[1] is the STREAMNAME and $push[2] is the first URI
      if ($push[1] === $streamName && $push[2] === $targetURI) {
        $pushId = $push[0]; // The push ID
        break;
      }
    }

    // If a matching push is found, stop it
    if ($pushId !== null) {
      $data = [
          "push_stop" => $pushId
      ];

      try {
        $this->send($data); // Send the push_stop request to MistServer
        $destination->push_is_started = 0;
        $destination->save();
        Log::info("Push stop successful for stream: {$streamName} with push ID: {$pushId}");
      } catch (\Exception $e) {
        Log::error("Failed to stop push for stream: {$streamName} with push ID: {$pushId}", ['exception' => $e->getMessage()]);
      }
    } else {
      Log::warning("No active push found matching stream: {$streamName} and URI: {$targetURI}");
      $destination->push_is_started = 0;
      $destination->save();
    }
  }

  public function configBackup() {
    try {
      // Log start
      Log::info('Initiating MistServer config backup process.');

      // Prepare request data
      $requestData = ["config_backup" => true];

      // Execute API call
      $response = $this->send($requestData);

      // New log for debugging the response structure
      Log::debug('Received API response for config backup.', ['response' => $response]);

      // Validate response structure and success status
      if (isset($response['LTS']) && $response['LTS'] == 1) {
        if (isset($response['authorize']['status']) && $response['authorize']['status'] == "OK") {
          if (isset($response['config_backup'])) {
            $configData = $response['config_backup'];

            // Encrypt and save config data
            $encryptedConfig = Crypt::encryptString(json_encode($configData));
            MistServerConfig::create(['config' => $encryptedConfig]);

            Log::info('MistServer configuration backup succeeded.');

            return response()->json(['message' => 'Config backup successful.'], 200);
          } else {
            Log::error('Config backup data missing in the response.', ['response' => $response]);

            return response()->json(['error' => 'Config backup data missing.'], 500);
          }
        } else {
          Log::error('Authorization failed or missing in the response.', ['response' => $response]);

          return response()->json(['error' => 'Authorization failed or missing.'], 500);
        }
      } else {
        Log::error('Invalid or unexpected API response structure.', ['response' => $response]);

        return response()->json(['error' => 'Invalid or unexpected API response structure.'], 500);
      }
    } catch (\Exception $e) {
      Log::error('Exception encountered during MistServer config backup.', ['exception' => $e]);

      return response()->json(['error' => 'An error occurred during config backup.', 'exception' => $e->getMessage()], 500);
    }
  }


  public function getLatestConfigBackup() {
    $latestConfig = MistServerConfig::latest()->first();

    if (!$latestConfig) {
      return null;
    }

    return Crypt::decryptString($latestConfig->config);
  }

  public function configRestore() {
    $configData = $this->getLatestConfigBackup();

    if (!$configData) {
      Log::error('No configuration backup found for restoration.');

      return response()->json(['error' => 'No configuration backup found.'], 404);
    }

    try {
      $requestData = ["config_restore" => json_decode($configData, true)]; // Convert the JSON string back to an array

      // Send the restore request to MistServer's API
      $this->send($requestData);

      // Since there's no response expected for the restore call, assume success if no exception is thrown
      Log::info('MistServer configuration restore initiated successfully.');

      return response()->json(['message' => 'Configuration restore initiated successfully.'], 200);
    } catch (\Exception $e) {
      Log::error('Exception during MistServer config restoration.', ['exception' => $e->getMessage()]);

      return response()->json(['error' => 'An error occurred during config restoration.'], 500);
    }
  }


}





//  protected function getPasswordWithChallenge() {
//    if ($this->challenge) {
//      // If there's a challenge, MD5 hash the password concatenated with the challenge string
//      return md5($this->password . $this->challenge);
//    }
//
//    // If no challenge has been received yet, just use the password as is or as per your initial encryption needs
//    return $this->password;
//  }
//

//
//
//  protected function prepareSendData($data)
//  {
//    $sendData = $data;
//    $sendData['authorize'] = [
//        'username' => $this->username,
//        'password' => $this->authString ? md5($this->password . $this->authString) : ''
//    ];
//    $sendData['minimal'] = true; // Enable minimal mode by default
//
//    return $sendData;
//  }
//
//  protected function executeRequest($data)
//  {
//    dump($this->host);
//    Log::debug("MistServer host URL: " . $this->host); // Laravel log for debugging
//    // Or use dump() for direct output if running in a local or development environment
//    // dump($this->host);
//
//    if (empty($this->host)) {
//      throw new \Exception('MistServer host URL is not configured.');
//    }
//
//    $response = Http::withHeaders([
//        'Content-Type' => 'application/x-www-form-urlencoded',
//    ])->post($this->host, ['command' => json_encode($data)]);
//
//    if ($response->failed()) {
//      return ['error' => 'Request to MistServer failed'];
//    }
//
//    return $response->json();
//  }
//
//public function createStream(array $details)
//{
//// Implementation for creating a stream
//}
//
//private function sendRequest($endpoint, $payload)
//{
//// Implementation for sending a request to MistServer
//}
//
//private function authenticate()
//{
//// Implementation for handling authentication with MistServer
//}
//
//// Additional methods for other interactions
//}
