<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use App\Models\InviteCode;

class UnclaimedInviteCode implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $invite_code = InviteCode::where('code', $value)->first();
        //get the invite code
        if ($invite_code != null) {
            if ($invite_code->claimed === 1) {
                $fail('This invite code has been claimed. Please tell the person who gave you the code.');
            }

        }
    }
}
