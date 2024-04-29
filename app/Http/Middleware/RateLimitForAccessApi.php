<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitForAccessApi
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
    public function handle(Request $request, Closure $next, $maxAttempts = 200, $decayMinutes = 1440): Response
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        $key = 'api_token_daily_' . $token;
        $currentTime = now();
        $endOfDay = $currentTime->copy()->endOfDay();
        $remainingTime = $endOfDay->diffInMinutes($currentTime);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return response()->json(['message' => 'Too Many Attempts'], 429);
        }
        $hitDuration = min($remainingTime, $decayMinutes);
        $this->limiter->hit($key, $hitDuration * 60);
        return $next($request);
    }
}
