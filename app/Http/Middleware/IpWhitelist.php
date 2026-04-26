<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpWhitelist
{
    /**
     * IP addresses that are always allowed (localhost).
     */
    protected array $alwaysAllowed = [
        '127.0.0.1',
        '::1',
    ];

    /**
     * Handle an incoming request.
     *
     * Checks if the client IP is in the whitelist.
     * Localhost is always allowed. Additional IPs are read from
     * the ALLOWED_IPS environment variable.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientIp = $request->ip();

        // Always allow localhost
        if (in_array($clientIp, $this->alwaysAllowed)) {
            return $next($request);
        }

        // Get whitelist from config
        $allowedIps = config('network.allowed_ips', []);

        if (!empty($allowedIps) && !in_array($clientIp, $allowedIps)) {
            abort(403, '⛔ Akses ditolak. IP Anda (' . $clientIp . ') tidak terdaftar dalam whitelist.');
        }

        // If ALLOWED_IPS is empty, allow all (no restriction)
        // This lets the user choose: set IPs for strict mode, or leave empty for open network
        return $next($request);
    }
}
