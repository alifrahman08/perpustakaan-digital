<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MemberMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()?->role === 'anggota', 403);

        return $next($request);
    }
}
