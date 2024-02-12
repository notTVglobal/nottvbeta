<?php

namespace App\Services;

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
          'status' => $response->status(),
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
