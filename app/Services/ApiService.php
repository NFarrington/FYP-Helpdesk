<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class ApiService extends Service
{
    /**
     * Get keys owned by a user.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[]
     */
    public function getOwnedBy(User $user)
    {
        return $user->tokens->sortBy('name');
    }

    /**
     * Create a new API token.
     *
     * @param array $attributes
     * @param \App\Models\User $user
     * @return \Laravel\Passport\PersonalAccessTokenResult
     */
    public function create(array $attributes, User $user)
    {
        return $user->createToken(array_get($attributes, 'name', 'Token '.Carbon::now()));
    }
}
