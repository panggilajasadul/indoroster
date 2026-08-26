<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeoApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-SEO-Token');
        $expectedToken = config('services.seo.api_token') ?: env('SEO_API_TOKEN', 'dev-seo-token-secret-123');

        if (! $token || $token !== $expectedToken) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Invalid or missing SEO API Token',
            ], 401);
        }

        return $next($request);
    }
}
