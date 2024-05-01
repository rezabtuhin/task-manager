<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitForCreateApiMiddleware
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 3, $decayMinutes = 5): Response
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        $key = 'api_token:' . $token;

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return response()->json(['message' => 'Too Many Attempts'], 429);
        }

        $this->limiter->hit($key, $decayMinutes*60);
        return $next($request);
    }
}
