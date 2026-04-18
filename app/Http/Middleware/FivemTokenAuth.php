<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FivemTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('services.fivem.token');

        if (empty($expected)) {
            return response()->json(['error' => 'API token not configured on server.'], 500);
        }

        if ($request->header('X-FiveM-Token') !== $expected) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }

        return $next($request);
    }
}
