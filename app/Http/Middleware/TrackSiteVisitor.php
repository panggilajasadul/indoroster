<?php

namespace App\Http\Middleware;

use App\Services\VisitorTrackerService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Track only successful GET requests
        if ($request->isMethod('GET') && $response->isSuccessful() && ! $request->ajax()) {
            VisitorTrackerService::track($request);
        }

        return $response;
    }
}
