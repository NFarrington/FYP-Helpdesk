<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Reauthenticate a user for extra security.
     *
     * @param string $email
     * @param string $password
     * @throws ValidationException
     */
    protected function reauthenticate(string $email, string $password)
    {
        if (!auth()->validate(['email' => $email, 'password' => $password])) {
            throw ValidationException::withMessages([
                'password' => [trans('auth.failed')],
            ]);
        }
    }
}
