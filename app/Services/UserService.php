<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;

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
     * Get all model instances the user can view.
     *
     * @param User $user
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getViewableBy(User $user)
    {
        return $user->can('view', User::class)
            ? User::orderBy('id')->paginate(20)
            : new LengthAwarePaginator(collect([$user]), 1, 20);
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
