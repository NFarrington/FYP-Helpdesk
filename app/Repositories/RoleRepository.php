<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends Repository
{
    /**
     * The order to sort the results by.
     *
     * @var string
     */
    protected $sortOrder = ['id', 'ASC'];

    /**
     * The model.
     *
     * @var Role
     */
    protected $model;

    /**
     * Initialise the repository.
     *
     * @param Role $model
     */
    public function __construct(Role $model)
    {
        $this->model = $model;
    }
}
