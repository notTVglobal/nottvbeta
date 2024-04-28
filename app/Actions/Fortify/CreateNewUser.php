<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserVideoSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Models\InviteCode;
use App\Rules\UnclaimedInviteCode;

class CreateNewUser implements CreatesNewUsers {
  use PasswordValidationRules;

  /**
   * Validate and create a newly registered user.
   *
   * @param array $input
   * @return \App\Models\User
   */
  public function create(array $input) {

    // set the role_id based on the invite code... if it's a standard user or VIP continue.
    // if it's a creator invite code then the user gets an error.
    $this->validateInput($input);

    $inviteCode = InviteCode::where('code', $input['invite_code'])->firstOrFail();

    DB::beginTransaction();

    try {

      // Determine role_id and VIP status based on invite code
      $roleId = 1; // default to standard user
      $isVip = 0; // default to not a VIP
      if ($inviteCode->user_role_id == 3) {
        $isVip = 1;
//        $roleId = 3; // Assuming 3 is the VIP user role_id -- we only use 2 userRoles now, Standard User and Creator
      }

      $user = User::create([
          'name'               => $input['name'],
          'email'              => $input['email'],
          'password'           => Hash::make($input['password']),
          'role_id'            => $roleId,
          'isVip'              => $isVip,
          'invite_code'        => $inviteCode->id,
          'terms_agreed_at '   => now(),
      ]);

      // Create video settings for the new user
      UserVideoSetting::create([
          'user_id' => $user->id,
      ]);

      // Check invite code volume and used count
      if ($inviteCode->volume && $inviteCode->used_count < $inviteCode->volume) {
        $inviteCode->used_count += 1;
        if ($inviteCode->used_count == $inviteCode->volume) {
          $inviteCode->claimed = true;
          $inviteCode->claimed_by = $user->id;
          $inviteCode->claimed_at = now();
        }
      }

      // Save inviteCode changes if used_count was incremented but not necessarily claimed
      $inviteCode->save();

      DB::commit();

      return $user;
    } catch (\Exception $e) {
      DB::rollBack();
      // Log the error or handle it as needed
      Log::error('Failed to create a new creator account', [
          'error_message' => $e->getMessage(),
          'stack_trace'   => $e->getTraceAsString(), // Optionally include the stack trace for more detailed debug information
          'input_name'    => $input['name'], // Be mindful of logging sensitive data like passwords
          'invite_code'   => $input['invite_code'], // Be mindful of logging sensitive data like passwords
      ]);

      return redirect()->route('home')->with(['error' => 'An unexpected error occurred. Please contact us to let us know.']);
    }
  }

  private function validateInput(array $input) {
    // validate the new user input and check the invite code
    Validator::make($input, [
        'name'                  => ['required', 'string', 'max:255'],
        'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password'              => $this->passwordRules(),
        'password_confirmation' => 'required|same:password',
        'terms'                 => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
//            'invite_code' => ['required', 'exists:invite_codes,code',]
        'invite_code'           => ['required', 'exists:invite_codes,code', new UnclaimedInviteCode([1, 3])],
    ])->validate();
  }

}
