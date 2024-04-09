<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateLastLogin
{
  public function handle($request, Closure $next)
  {
    if (Auth::check()) {
      Auth::user()->update(['last_login_at' => now()]);
    }

    return $next($request);
  }
}
