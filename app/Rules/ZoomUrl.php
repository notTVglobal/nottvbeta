<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ZoomUrl implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
      // Check if the URL is a valid URL
      if (!filter_var($value, FILTER_VALIDATE_URL)) {
        return false;
      }

      // Parse the URL and check the host and path
      $parsedUrl = parse_url($value);
      if (!isset($parsedUrl['host'], $parsedUrl['path'])) {
        return false;
      }

      // Check if the domain is a zoom.us domain
      if (!preg_match('/\bzoom\.us$/', $parsedUrl['host'])) {
        return false;
      }

      // Further checks can be added here for the path if necessary
      return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
      return 'The :attribute must be a valid Zoom meeting URL.';
    }
}
