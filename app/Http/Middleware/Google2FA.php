<?php

namespace App\Http\Middleware;

use Closure;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class Google2FA
{
    /**
     * The URIs that should be excluded from 2FA verification.
     *
     * @var array
     */
    protected $except = [
        'login/two-factor',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->inExceptArray($request) || ($request->user() && !$request->user()->google2fa_secret)) {
            return $next($request);
        }

        $authenticator = app(Authenticator::class)->boot($request);

        if ($authenticator->isAuthenticated()) {
            return $next($request);
        }

        return redirect()->guest(route('login.2fa'));
    }

    /**
     * Determine if the request has a URI that should pass through 2FA verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     *
     * @internal Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
