<?php
namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Session;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
  public function toResponse($request): \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response {
    $user = Auth::user();
    $user->increment('logins_count');

    // Set session variable to show notification on second login
    if ($user->logins_count == 2) {
      Session::put('show_homescreen_popup', true);
    } else {
      Session::forget('show_homescreen_popup');
    }

    return redirect()->intended(config('fortify.home'));
  }
}
