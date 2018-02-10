<?php

namespace App\Repositories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Collection;

interface AnnouncementRepository extends Repository
{
    /**
     * Return all published announcements.
     *
     * @return Collection|Announcement[]
     */
    public function getPublished();
}
