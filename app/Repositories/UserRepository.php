<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
    /**
     * The order to sort the results by.
     *
     * @var string
     */
    protected $sortOrder = ['created_at', 'DESC'];

    /**
     * The model.
     *
     * @var User
     */
    protected $model;

    /**
     * Initialise the repository.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }
}
