<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\SchedulesIndex;

class BroadcastDetailsService
{


  /**
   * @throws ValidationException
   */
  public function decodeAndValidateDetails($nextBroadcastDetails) {
    Log::debug('Before decodeAndValidateDetails: ' . var_export($nextBroadcastDetails, true) );
    // Decode JSON details
    $nextBroadcastDetailsDecoded = json_decode($nextBroadcastDetails, true);

    Log::debug('After decodeAndValidateDetails: ' . var_export($nextBroadcastDetailsDecoded, true) );

    // Define validation rules
    $rules = [
        'duration_minutes' => 'sometimes|integer|max:5',
        'zoomLink'         => 'sometimes|url|max:255',
    ];

    // Validate the decoded details
    $validator = Validator::make($nextBroadcastDetailsDecoded ?? [], $rules);

    if ($validator->fails()) {
//      throw ValidationException::withMessages(['next_broadcast_details' => 'Invalid JSON format']);
      throw new ValidationException($validator);
    }

    // Log the decoded details
    Log::debug('Decoded next_broadcast_details', $nextBroadcastDetailsDecoded);

    return $nextBroadcastDetailsDecoded;
  }

}
