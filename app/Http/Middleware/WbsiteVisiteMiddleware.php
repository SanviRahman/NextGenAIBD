<?php

namespace App\Http\Middleware;

use App\Models\WebsiteViews;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WbsiteVisiteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $websiteViews = WebsiteViews::first();
        if ($websiteViews) {
            $websiteViews->increment('view', 1);
        } else {
            abort(404);
        }

        return $next($request);
    }
}
