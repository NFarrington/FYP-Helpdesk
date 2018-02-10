<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    /**
     * The instantiated Eloquent model class.
     *
     * @var Model
     */
    protected $model;

    /**
     * Return all announcements.
     *
     * @return Collection|Model[]
     */
    public function getAll()
    {
        return $this->model->all();
    }
}
