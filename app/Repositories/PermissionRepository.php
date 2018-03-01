<?php

namespace App\Repositories;

use App\Models\Permission;

class PermissionRepository extends Repository
{
    /**
     * The order to sort the results by.
     *
     * @var string
     */
    protected $sortOrder = ['key', 'ASC'];

    /**
     * The model.
     *
     * @var Permission
     */
    protected $model;

    /**
     * Initialise the repository.
     *
     * @param Permission $model
     */
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }
}
