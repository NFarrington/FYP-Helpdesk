<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    /**
     * The order to sort the results by.
     *
     * @var string
     */
    protected $sortOrder = ['id', 'ASC'];

    /**
     * The instantiated Eloquent model class.
     *
     * @var \Eloquent|Model
     */
    protected $model;

    /**
     * Return all announcements.
     *
     * @return Collection|Model[]
     */
    public function getAll()
    {
        return $this->model->orderBy(...$this->sortOrder)->get();
    }

    /**
     * Get a specific model by its ID.
     *
     * @param int $id
     * @return \Eloquent|Model|null
     */
    public function getById(int $id)
    {
        return $this->model->find($id);
    }
}
