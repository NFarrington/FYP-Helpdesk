<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AnnouncementRepository;

class AnnouncementService
{
    /**
     * The announcement repository.
     *
     * @var AnnouncementRepository
     */
    private $repository;

    /**
     * Initialise the service.
     *
     * @param AnnouncementRepository $repository
     */
    public function __construct(AnnouncementRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all the announcements the user can view.
     *
     * @param User $user
     * @return \App\Models\Announcement[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getViewableBy(User $user)
    {
        return $user->hasPermission('announcements.view')
            ? $this->repository->getAll()
            : $this->repository->getPublished();
    }
}
