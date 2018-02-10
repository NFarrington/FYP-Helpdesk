<?php

namespace App\Repositories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class EloquentAnnouncementRepository extends EloquentRepository implements AnnouncementRepository
{
    /**
     * EloquentRepository constructor.
     *
     * @param Announcement $model
     * @param Request $request
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
