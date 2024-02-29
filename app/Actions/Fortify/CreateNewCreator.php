<?php

namespace App\Actions\Fortify;

use App\Models\User;
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

class CreateNewCreator implements CreatesNewUsers {
  use PasswordValidationRules;

  /**
   * Validate and create a newly registered user.
   *
   * @param array $input
   * @return \App\Models\User
   */
  public function create(array $input) {

    $this->validateInput($input);

    $inviteCode = InviteCode::where('code', $input['invite_code'])->firstOrFail();

    DB::beginTransaction();

    try {
      $user = User::create([
          'name'           => $input['name'],
          'email'          => $input['email'],
          'password'       => Hash::make($input['password']),
          'role_id'        => 4, // creator role_id
          'invite_code_id' => $inviteCode->id,
      ]);

      $inviteCode->claimed = true;
      $inviteCode->claimed_by = $user->id;
      $inviteCode->claimed_at = now();
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
          'invite_code'   => $input['invite_code'],
      ]);

      return redirect()->route('home')->with(['error' => 'An unexpected error occurred. Please contact us to let us know.']);
    }
  }

  private function validateInput(array $input) {
    Validator::make($input, [
        'name'                  => ['required', 'string', 'max:255'],
        'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'address1'              => ['required', 'string', 'max:255'],
        'address2'              => ['nullable', 'string', 'max:255'],
        'city'                  => ['required', 'string', 'max:255'],
        'province'              => ['required', 'string', 'max:255'],
        'country'               => ['required', 'string', 'max:255'],
        'postalCode'            => ['required', 'string', 'max:255'],
        'phone'                 => ['required', 'string', 'max:255'],
        'password'              => $this->passwordRules(),
        'password_confirmation' => 'required|same:password',
        'terms'                 => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        'invite_code'           => ['required', new UnclaimedInviteCode([4])],
    ])->validate();
  }

}
