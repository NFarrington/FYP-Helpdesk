<?php

namespace App\Repositories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Collection;

class AnnouncementRepository extends Repository
{
    /**
     * EloquentRepository constructor.
     *
     * @param Announcement $model
     */
    public function __construct(Announcement $model)
    {
        $this->model = $model;
    }

    /**
     * Return all published announcements.
     *
     * @return Collection|Announcement[]
     */
    public function getPublished()
    {
        return $this->model->published()->get();
    }
}
