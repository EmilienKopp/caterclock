<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestHandlingTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        $end = microtime(true);

        $duration = $end - $start;

        $response->headers->set('X-Request-Handling-Time', $duration);

        Log::info('Request handled:', [
            'duration' => $duration*1000 . ' ms',
            'uri' => $request->getUri(),
            'method' => $request->getMethod(),
            
        ]);

        return $response;
    }
}
