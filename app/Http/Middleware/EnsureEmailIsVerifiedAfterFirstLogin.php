<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerifiedAfterFirstLogin
{
  /**
   * Handle an incoming request.
   *
   * @param Request $request
   * @param Closure $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next): mixed {
    $user = Auth::user();

    // If the user is authenticated but hasn't verified their email and it's not their first login
    if ($user && !$user->hasVerifiedEmail() && $user->logins_count > 1) {
      return redirect()->route('verification.notice');
    }

    return $next($request);
  }
}
