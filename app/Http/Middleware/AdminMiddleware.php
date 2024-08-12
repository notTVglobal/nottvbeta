<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware {
  public function handle($request, Closure $next) {
    if (Auth::check() && Auth::user()->isAdmin) {
      return $next($request);
    }

    // Redirect or show unauthorized response
    return redirect()->route('home')->with('error', 'Unauthorized access');
  }
}
