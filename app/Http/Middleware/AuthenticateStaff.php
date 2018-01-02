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

            $this->authenticateUser('admin', $isAdmin, $user);
            $this->authenticateUser('agent', $isAgent, $user);
        }

        return $next($request);
    }

    /**
     * Forces a user's authenticated state to comply with its expected state.
     *
     * @param string $guard
     * @param bool $shouldLogin
     * @param \App\Models\User $user
     * @return void
     */
    protected function authenticateUser($guard, $shouldLogin, $user)
    {
        $guard = Auth::guard($guard);
        $loggedIn = $guard->check();

        if ($shouldLogin && !$loggedIn) {
            $guard->login($user);
        } elseif (!$shouldLogin && $loggedIn) {
            $guard->logout();
        }
    }
}
