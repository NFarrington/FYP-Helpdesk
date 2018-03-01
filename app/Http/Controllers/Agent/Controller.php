<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Role;
use Closure;

abstract class Controller extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, Closure $next) {
            $this->authorize('has', Role::agent());

            return $next($request);
        });
    }
}
