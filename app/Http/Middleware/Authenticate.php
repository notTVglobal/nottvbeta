<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware {
  /**
   * Get the path the user should be redirected to when they are not authenticated.
   *
   * @param \Illuminate\Http\Request $request
   * @return string|null
   */
  protected function redirectTo($request) {
    if (! $request->expectsJson()) {
      // Check if the request URI is for the news route
      if ($request->is('news') || $request->is('news/*')) {
        // Redirect to the public news route
        // Adjust the route name or path as per your routing setup
        return url('public/news');
      }

      // Default redirect to login for other routes
      return route('login');
    }
  }
}
