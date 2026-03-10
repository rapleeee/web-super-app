<?php

namespace App\Http\Middleware;

use App\Services\MaintenanceQuoteService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class EnsureApplicationNotInCustomMaintenanceMode
{
    public function __construct(private MaintenanceQuoteService $maintenanceQuoteService) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isMaintenanceMode = (bool) Cache::get('app_maintenance_enabled', false);

        if (! $isMaintenanceMode) {
            return $next($request);
        }

        if ($this->isAuthRoute($request)) {
            return $next($request);
        }

        $user = $request->user();
        $isPrivilegedUser = $user && ($user->role === 'admin' || $user->email === 'test@example.com');

        if ($isPrivilegedUser) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Aplikasi sedang dalam maintenance.',
            ], 503);
        }

        return response()->view('maintenance', [
            'quote' => $this->maintenanceQuoteService->randomQuote(),
        ], 503);
    }

    private function isAuthRoute(Request $request): bool
    {
        return $request->is('login')
            || $request->is('logout')
            || $request->is('forgot-password')
            || $request->is('reset-password')
            || $request->is('reset-password/*')
            || $request->is('verify-email')
            || $request->is('verify-email/*')
            || $request->is('email/verification-notification')
            || $request->is('confirm-password')
            || $request->is('password')
            || $request->routeIs('login')
            || $request->routeIs('password.*')
            || $request->routeIs('verification.*')
            || $request->routeIs('logout');
    }

    public static function cacheKey(): string
    {
        return 'app_maintenance_enabled';
    }
}
