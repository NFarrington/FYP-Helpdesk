<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;

abstract class Service
{
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
