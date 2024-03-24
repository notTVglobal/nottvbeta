<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\InviteCode;
use Exception;
use Illuminate\Support\Carbon;

class InviteCodeService
{

  protected function getInviteCodeSettings() {
    $settings = AppSetting::query()->firstOrFail()->invite_code_settings;
    return json_decode($settings, true);
  }

  /**
   * Validates an invite code.
   *
   * @param string $code
   * @return array
   */
  public function validateCode(InviteCode $inviteCode): array
  {
//    $inviteCode = InviteCode::where('code', $inviteCode)->first();

//    if (!$inviteCode) {
//      return ['success' => false, 'message' => 'Oops! It looks like your invite code might have been mistyped or does not exist.'];
//    }

    if ($inviteCode->claimed) {
      return ['success' => false, 'message' => 'This invite code has already embarked on its journey with another creator.'];
    }

    if ($inviteCode->expiry_date && Carbon::now()->greaterThan($inviteCode->expiry_date)) {
      return ['success' => false, 'message' => 'Time flies! This invite code has expired, but don’t let that stop your creative journey.'];
    }

    // Code is valid
    return ['success' => true, 'message' => 'Invite code is valid.', 'code' => $inviteCode];
  }

  /**
   * @throws Exception
   */
  public function generateForCreator($userId, $type): void {
    $settings = $this->getInviteCodeSettings();
    // Fetch the number of codes to generate based on the user type
    $numberOfCodes = match ($type) {
      'creator' => $settings['creatorCodes'] ?? 0,
      'viewer' => $settings['viewerCodes'] ?? 0,
      'vip' => $settings['vipCodes'] ?? 0,
      default => throw new Exception("Invalid type: {$type}"),
    };
    // Determine the role ID based on the type
    if ($numberOfCodes > 0) {
      // Determine the role ID based on the type
      $roleId = match ($type) {
        'creator' => 4,
        'viewer' => 1,
        'vip' => 3,
        default => throw new Exception("Invalid type: {$type}"),
      };
      // Create a new invite code with the determined volume if numberOfCodes > 0
      InviteCode::create([
          'code' => $this->generateUniqueInviteCode(),
          'created_by' => $userId,
          'user_role_id' => $roleId,
          'volume' => $numberOfCodes, // This will now only be set if numberOfCodes > 0
          'expiry_date' => null, // Assuming no expiry
      ]);
    }
  }

  public function generateUniqueInviteCode(): string {
    $adjectives = [
        'Sunny', 'Happy', 'Joyful', 'Vibrant', 'Serene', 'Golden', 'Silent', 'Gleaming', 'Tranquil',
        'Azure', 'Emerald', 'Scarlet', 'Pastel', 'Twilight', 'Dewy', 'Flourishing', 'Luminous', 'Pristine',
        'Radiant', 'Sparkling', 'Majestic', 'Ethereal', 'Harmonious', 'Blossoming', 'Blissful', 'Illuminated',
        'Peaceful', 'Refreshing', 'Enchanting'
    ];


    $nouns = [
        'Mountain', 'River', 'Forest', 'Valley', 'Ocean', 'Lake', 'Tree', 'Cloud', 'Bird', 'Flower',
        'Meadow', 'Glacier', 'Canyon', 'Garden', 'Peak', 'Fjord', 'Orchard', 'Vineyard',
        'Rainbow', 'Butterfly', 'Breeze', 'Moonlight', 'Starlight', 'Sunset', 'Dawn', 'Horizon', 'Pond',
        'Sanctuary', 'Brook', 'Sunrise', 'Haven', 'Paradise', 'Bloom', 'Heaven'
    ];


    do {
      $code = $adjectives[array_rand($adjectives)] . $nouns[array_rand($nouns)];

      // Check uniqueness and append two random digits if necessary
      if (InviteCode::where('code', $code)->exists()) {
        $code .= rand(10, 99);
      }

      // Double-check the modified code's uniqueness
      $unique = !InviteCode::where('code', $code)->exists();
    } while (!$unique);

    return $code;
  }
}
