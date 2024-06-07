<?php
namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
  public function toResponse($request)
  {
    $user = Auth::user();
    $user->increment('logins_count');

    return redirect()->intended(config('fortify.home'));
  }
}
