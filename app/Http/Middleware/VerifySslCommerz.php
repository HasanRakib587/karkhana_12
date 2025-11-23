<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySslCommerz
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Example: verify the 'signature' query param matches order ID
        $orderId = $request->input('order');
        $signature = $request->input('signature');

        if (!$orderId || !$signature || sha1($orderId) !== $signature) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
