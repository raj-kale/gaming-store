<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || ! ($user->is_admin ?? false)) {
            return abort(403, 'Admins only');
        }

        return $next($request);
    }
}
