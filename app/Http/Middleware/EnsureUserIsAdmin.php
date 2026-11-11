<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Đã qua middleware auth nên chắc chắn có user; không phải admin -> 403
        abort_unless($request->user()?->isAdmin(), 403);

        return $next($request);
    }
}
