<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PreventSessionByUserAgent
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse {
      // List of user agents to not start sessions for
      $blockedUserAgents = ['DigitalOcean Uptime Probe 0.22.0 (https://digitalocean.com)'];

      // Check if the current user agent is in the blocked list
      if (in_array($request->header('User-Agent'), $blockedUserAgents)) {
        // Prevent session from starting
        config(['session.driver' => 'array']);
      }

      return $next($request);
    }
}
