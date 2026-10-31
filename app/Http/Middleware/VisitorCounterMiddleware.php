<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Torann\GeoIP\Facades\GeoIP;
use Symfony\Component\HttpFoundation\Response;

class VisitorCounterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        $agent = new Agent();
        $browser = $agent->browser();
        $country = $this->getCountryFromIpAddress($ipAddress);

        if (!Visitor::where('ip_address', $ipAddress)->whereDate('created_at', date('Y-m-d'))->exists()) {
            Visitor::create([
                'ip_address' => $ipAddress,
                'created_at' => now(),
                'device' => $userAgent,
                'browser' => $browser,
                'country' => $country,
                'status' => 1,
            ]);
        }

        return $next($request);
    }

    protected function getCountryFromIpAddress($ipAddress)
    {
        // $location = GeoIP::getLocation($ipAddress)->country;
        // $location = GeoIP::getLocation($ipAddress)->country;
        return 'Unknown';
        // Access the country property from the location data
        // return $location->country ?? 'Unknown';
    }
}
