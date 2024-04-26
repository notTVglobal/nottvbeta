<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventSessionByUserAgent
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * * @param Closure $next
 */
    public function handle(Request $request, Closure $next) {
      // List of user agents to not start sessions for
      $blockedUserAgents = ['DigitalOcean Uptime Probe 0.22.0 (https://digitalocean.com)', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; ClaudeBot/1.0; +claudebot@anthropic.com)'];

      // Check if the current user agent is in the blocked list
      if (in_array($request->header('User-Agent'), $blockedUserAgents)) {
        // Prevent session from starting
        config(['session.driver' => 'array']);
      }

      return $next($request);
    }
}
