<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

class VerifyRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string[] ...$roles
     * @return mixed
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!$user = $request->user()) {
            throw new AuthenticationException(); // @codeCoverageIgnore
        }

        foreach ($roles as $role) {
            if (!$user->hasRole($role)) {
                throw new AuthorizationException();
            }
        }

        return $next($request);
    }
}
