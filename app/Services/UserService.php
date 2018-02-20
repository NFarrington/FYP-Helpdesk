<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService extends Service
{
    /**
     * The repository.
     *
     * @var UserRepository
     */
    protected $repository;

    /**
     * Initialise the service.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Update the user given user.
     *
     * @param User $user
     * @param array $attributes
     * @return void
     */
    public function update(User $user, array $attributes)
    {
        $user->email = $attributes['email'];
        if ($newPassword = array_get($attributes, 'new_password')) {
            $user->password = app('hash')->make($newPassword);
        }

        $user->save();
    }
}
