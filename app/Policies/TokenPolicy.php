<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Laravel\Passport\Token;

class TokenPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $user
     * @param \Laravel\Passport\Token|null $token
     * @return mixed
     */
    public function delete(User $user, Token $token)
    {
        $isAdmin = $user->hasRole(Role::admin());

        return $user->id === $token->user_id || $isAdmin;
    }
}
