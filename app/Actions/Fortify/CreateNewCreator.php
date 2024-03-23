<?php

namespace App\Actions\Fortify;

use App\Models\NewsCountry;
use App\Models\NewsPostalCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
   * @return RedirectResponse
   * @throws ValidationException
   */
  public function create(array $input): RedirectResponse {

    $this->validateInput($input);

    $inviteCode = InviteCode::where('code', $input['invite_code'])->firstOrFail();

    DB::beginTransaction();

    try {
      $user = User::create([
          'name'             => $input['name'],
          'email'            => $input['email'],
          'password'         => Hash::make($input['password']),
          'role_id'          => 4, // creator role_id
          'invite_code_id'   => $inviteCode->id,
          'terms_agreed_at ' => now(),
          'address1'         => ['sometimes', 'string', 'max:255'],
          'address2'         => ['sometimes', 'string', 'max:255'],
          'city'             => ['sometimes', 'string', 'max:255'],
          'province'         => ['sometimes', 'string', 'max:255'],
          'country'          => ['required', 'string', 'max:255'],
          'postalCode'       => ['required', 'string', 'max:255'],
          'phone'            => ['sometimes', 'string', 'max:255'],
      ]);

      // Increment the used count
      $inviteCode->used_count += 1;
      // Check if the invite code should be claimed
      if ($inviteCode->volume <= $inviteCode->used_count) {
        $inviteCode->claimed = true;
        $inviteCode->claimed_at = now();
      }
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

  /**
   * @throws ValidationException
   */
  private function validateInput(array $input): void {
    Validator::make($input, [
        'name'                  => ['required', 'string', 'max:255'],
        'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'address1'              => ['sometimes', 'string', 'max:255'],
        'address2'              => ['sometimes', 'string', 'max:255'],
        'city'                  => ['sometimes', 'string', 'max:255'],
        'province'              => ['sometimes', 'string', 'max:255'],
        'country'               => ['required', 'exists:news_countries,id'],
        'phone'                 => ['sometimes', 'string', 'max:255'],
        'password'              => $this->passwordRules(),
        'password_confirmation' => 'required|same:password',
        'terms'                 => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        'invite_code'           => ['required', new UnclaimedInviteCode([4])],
        'postalCode' => [
            'sometimes',
            'string',
            'max:255',
            Rule::requiredIf(function () use ($input) {
              $canadaId = NewsCountry::where('name', 'Canada')->first()->id ?? null;
              return isset($input['country']) && $input['country'] == $canadaId;
            }),
            function ($attribute, $value, $fail) {
              // Normalize the input: remove spaces and dashes, convert to uppercase
              $normalizedValue = strtoupper(str_replace([' ', '-'], '', $value));

              // Custom validation to check if the postal code exists in the NewsPostalCode table
              if (!NewsPostalCode::where('postal_code', $normalizedValue)->exists()) {
                $fail($attribute.' is invalid.');
              }
            }
        ],
    ])->validate();
  }

}
