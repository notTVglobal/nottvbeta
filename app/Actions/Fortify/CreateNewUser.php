<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Models\InviteCode;
use App\Rules\UnclaimedInviteCode;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {

//        // get the invite code
//        $code = $input['invite_code'];
//        $invite_code = InviteCode::where('code',$code)->first();
////        $claimed = $invite_code->claimed;
//        $claimed = 1;

//        // check the invite code
//        function checkInviteCode($claimed):bool {
//            if ($claimed === 1) {
//                return true;
//            }
//            return false;
//        }

        // validate the new user input and check the invite code
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'password_confirmation' => 'required|same:password',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
//            'invite_code' => ['required', 'exists:invite_codes,code',]
            'invite_code' => ['required', 'exists:invite_codes,code', new UnclaimedInviteCode],
        ])->validate();

                // get the invite code
        $code = $input['invite_code'];
        $invite_code = InviteCode::where('code',$code)->first();
        // claim the invite code
        $invite_code->code = $code;
        $invite_code->claimed = true;
        $invite_code->save();



        // create the new user
        return $userId  = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role_id' => '1',
            'address1' => null,
            'address2' => null,
            'city' => null,
            'province' => null,
            'country' => null,
            'postalCode' => null,
            'phone' => null,
            'creatorNumber' => null,
            'subscriptionStatus' => null,
            'invite_code' => $invite_code->id,
        ]);
    }
}
