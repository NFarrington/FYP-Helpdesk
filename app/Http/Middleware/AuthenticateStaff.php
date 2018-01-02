<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($user = $request->user('user')) {
            $isAdmin = $user->hasRole(Role::ROLE_ADMIN);
            $isAgent = $isAdmin || $user->hasRole(Role::ROLE_AGENT);

            if ($isAdmin && !Auth::guard('admin')->check()) {
                Auth::guard('admin')->login($request->user());
            } elseif (!$isAdmin && Auth::guard('admin')->check()) {
                Auth::guard('admin')->logout();
            }

            if ($isAgent && !Auth::guard('agent')->check()) {
                Auth::guard('agent')->login($request->user());
            } elseif (!$isAgent && Auth::guard('agent')->check()) {
                Auth::guard('agent')->logout();
            }
        }

        return $next($request);
    }
}
