<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface Repository
{
    /**
     * Return all announcements.
     *
     * @return Collection|Model[]
     */
    public function getAll();
}
