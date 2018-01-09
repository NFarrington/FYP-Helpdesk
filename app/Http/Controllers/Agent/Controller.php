<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller as BaseController;

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
        $this->middleware('role:agent');
    }
}
