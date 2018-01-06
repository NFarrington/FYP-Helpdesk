<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class VerifyRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!$user = $request->user('user')) {
            throw new AuthenticationException();
        }

        foreach ($roles as $role) {
            if (!$user->hasRole($role)) {
                throw new AuthorizationException();
            }
        }

        return $next($request);
    }
}
