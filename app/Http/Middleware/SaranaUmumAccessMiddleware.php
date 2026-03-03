<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SaranaUmumAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (! $user) {
            abort(403);
        }

        if (in_array($user->role, ['admin', 'pejabat', 'laboran'], true)) {
            return $next($request);
        }

        if ($user->role !== 'staff') {
            abort(403);
        }

        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return $next($request);
        }

        $staffAllowedWriteRoutes = [
            'sarana-umum.maintenance-log.store',
            'sarana-umum.berita-acara.store',
        ];

        if (in_array($request->route()?->getName(), $staffAllowedWriteRoutes, true)) {
            return $next($request);
        }

        abort(403, 'Akses tidak diizinkan untuk peran staff.');
    }
}
