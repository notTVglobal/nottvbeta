<?php

namespace App\Rules;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class IsValidPassword implements Rule
{
    /**
     * Determine if the Length Validation Rule passes.
     *
     * @var boolean
     */
    public $lengthPasses = true;

    /**
     * Determine if the Uppercase Validation Rule passes.
     *
     * @var boolean
     */
    public $uppercasePasses = true;

    /**
     * Determine if the Numeric Validation Rule passes.
     *
     * @var boolean
     */
    public $numericPasses = true;

    /**
     * Determine if the Special Character Validation Rule passes.
     *
     * @var boolean
     */
    public $specialCharacterPasses = true;

    /**
     * Determine if the password has been involved in a data breach.
     *
     * @var boolean
     */
    public $uncompromised = true;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->uncompromised = Password::min(10)->uncompromised();
        $this->lengthPasses = (Str::length($value) >= 10);
        $this->uppercasePasses = (Str::lower($value) !== $value);
        $this->numericPasses = ((bool) preg_match('/[0-9]/', $value));
        $this->specialCharacterPasses = ((bool) preg_match('/[^A-Za-z0-9]/', $value));

        return ($this->lengthPasses && $this->uppercasePasses && $this->numericPasses && $this->specialCharacterPasses);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
     public function message()
     {
         return match (true) {
             !$this->uppercasePasses
             && $this->numericPasses
             && $this->specialCharacterPasses => 'The :attribute must be at least 10 characters and contain at least one uppercase character.',
             !$this->numericPasses
             && $this->uppercasePasses
             && $this->specialCharacterPasses => 'The :attribute must be at least 10 characters and contain at least one number.',
             !$this->specialCharacterPasses
             && $this->uppercasePasses
             && $this->numericPasses => 'The :attribute must be at least 10 characters and contain at least one special character.',
             !$this->uppercasePasses
             && !$this->numericPasses
             && $this->specialCharacterPasses => 'The :attribute must be at least 10 characters and contain at least one uppercase character and one number.',
             !$this->uppercasePasses
             && !$this->specialCharacterPasses
             && $this->numericPasses => 'The :attribute must be at least 10 characters and contain at least one uppercase character and one special character.',
             !$this->uppercasePasses
             && !$this->numericPasses
             && !$this->specialCharacterPasses => 'The :attribute must be at least 10 characters and contain at least one uppercase character, one number, and one special character.',
             default => 'The :attribute must be at least 10 characters.',
         };
    }
}
