<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * NativePHP's iOS scheme handler forwards the request body but doesn't set
 * the Content-Length header, so PHP never auto-populates $_POST for
 * application/x-www-form-urlencoded requests. The raw body is still
 * readable via $request->getContent(); this middleware re-parses it into
 * the request input when Laravel's own parsing came up empty.
 *
 * @url https://github.com/NativePHP/mobile-air/issues/87
 */
class HotfixForPostAction
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('POST') && ! $request->isMethod('PUT') && ! $request->isMethod('PATCH')) {
            return $next($request);
        }

        $raw = $request->getContent();
        if ($raw === '') {
            return $next($request);
        }

        $contentType = (string) $request->header('content-type', '');

        if (str_contains($contentType, 'application/json')) {
            $parsed = json_decode($raw, true);
        } else {
            parse_str($raw, $parsed);
        }
        if (is_array($parsed)) {
            $request->merge($parsed);
        }

        return $next($request);
    }
}
