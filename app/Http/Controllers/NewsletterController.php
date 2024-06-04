<?php

namespace App\Http\Controllers;

use App\Services\BrevoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
  protected BrevoService $brevoService;

  public function __construct(BrevoService $brevoService)
  {
    $this->brevoService = $brevoService;
  }

  public function subscribe(Request $request): \Illuminate\Http\JsonResponse {
    $request->validate([
        'email' => 'required|email'
    ]);

    $email = $request->input('email');
    $response = $this->brevoService->addUserToList($email);

    // Log the response for debugging purposes
//    Log::info('Brevo API response: ', $response);

    if (isset($response['code']) && $response['code'] === 'duplicate_parameter') {
      return response()->json(['message' => 'You are already subscribed to the newsletter.'], 200);
    }

    if (isset($response['id'])) {
      return response()->json(['message' => 'Thank you for signing up!'], 200);
    }

    // Handle other unexpected responses
    if (isset($response['message'])) {
      return response()->json(['message' => 'Failed to subscribe: ' . $response['message']], 500);
    }

    return response()->json(['message' => 'Failed to subscribe. Please try again later.'], 500);
  }
}
