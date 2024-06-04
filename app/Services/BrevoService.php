<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrevoService
{
  protected string $apiKey;
  protected int $listId;

  public function __construct()
  {
    $this->apiKey = config('services.brevo.api_key');
    $this->listId = (int) config('services.brevo.list_id');
  }

  public function addUserToList($email)
  {
    $response = Http::withHeaders([
        'api-key' => $this->apiKey,
        'Content-Type' => 'application/json'
    ])->post('https://api.sendinblue.com/v3/contacts', [
        'email' => $email,
        'listIds' => [$this->listId]
    ]);

    return $response->json();
  }
}
