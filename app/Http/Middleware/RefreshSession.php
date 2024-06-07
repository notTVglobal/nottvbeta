<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshSession
{

  protected int $refreshInterval = 15; // Set interval in minutes for refreshing the session

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */

  public function handle($request, Closure $next)
  {
    if (!auth()->check()) {
      $lastRefresh = session('lastRefreshTime');
      $currentTime = now();

      if ($lastRefresh && $currentTime->diffInMinutes($lastRefresh) >= $this->refreshInterval) {
        session(['lastRefreshTime' => $currentTime]);
        session()->save();
      } elseif (!$lastRefresh) {
        session(['lastRefreshTime' => $currentTime]);
        session()->save();
      }
    }

    return $next($request);
  }
}
