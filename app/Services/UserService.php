<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

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
     * @return \App\Models\User[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getViewableBy(User $user)
    {
        return $user->can('view', User::class)
            ? User::orderBy('id')->get()
            : collect([$user]);
    }

    /**
     * Create a new user.
     *
     * @param array $attributes
     * @return \App\Models\User
     */
    public function create(array $attributes)
    {
        $user = new User(array_only($attributes, ['name', 'email']));
        $user->email_verified = true;
        $user->password = Hash::make($attributes['password']);
        $user->save();
        $user->roles()->sync(array_get($attributes, 'roles'));
        $user->departments()->sync(array_get($attributes, 'departments'));

        return $user;
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
