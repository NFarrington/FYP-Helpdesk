<?php

namespace App\Repositories;

use App\Models\Department;

class DepartmentRepository extends Repository
{
    /**
     * The order to sort the results by.
     *
     * @var string
     */
    protected $sortOrder = ['name', 'ASC'];

    /**
     * The model.
     *
     * @var Department
     */
    protected $model;

    /**
     * Initialise the repository.
     *
     * @param Department $model
     */
    public function __construct(Department $model)
    {
        $this->model = $model;
    }

    /**
     * Get all external-facing departments.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getExternal()
    {
        return $this->model->external()->orderBy(...$this->sortOrder)->get();
    }
}
