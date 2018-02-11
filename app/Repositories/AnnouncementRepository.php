<?php

namespace App\Repositories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Collection;

class AnnouncementRepository extends Repository
{
    /**
     * The order to sort the results by.
     *
     * @var string
     */
    protected $sortOrder = ['updated_at', 'DESC'];

    /**
     * The model.
     *
     * @var Announcement
     */
    protected $model;

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
        return $this->model->published()->orderBy(...$this->sortOrder)->get();
    }
}
