<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifySslcommerzIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Define allowed IPs based on mode
        $allowedIps = config('sslcommerz.is_live')
            ? ['103.17.180.18', '103.17.180.19', '103.17.180.234'] // Live IPs
            : ['103.17.180.74', '103.17.180.75']; // Sandbox IPs

        $remoteIp = $request->ip();

        if (! in_array($remoteIp, $allowedIps, true)) {
            Log::warning('SSLCommerz IPN blocked - unauthorized IP', [
                'ip' => $remoteIp,
                'allowed' => $allowedIps,
            ]);
            abort(403, 'Unauthorized IP address.');
        }

        return $next($request);
    }
}
