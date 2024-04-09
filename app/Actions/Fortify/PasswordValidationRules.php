<?php

namespace App\Actions\Fortify;

//use Laravel\Fortify\Rules\Password;
use App\Rules\IsValidPassword;
use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
//        return ['required', 'string', new isValidPassword, 'confirmed'];
        return [
                'required',
                Password::min(10)
                ->uncompromised()
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
        ];
    }

}
