<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\User;
use App\Repositories\AnnouncementRepository;

class AnnouncementService extends Service
{
    /**
     * The repository.
     *
     * @var AnnouncementRepository
     */
    protected $repository;

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
     * Create a new announcement.
     *
     * @param array $attributes
     * @param User $user
     * @return Announcement
     */
    public function create(array $attributes, User $user)
    {
        $announcement = new Announcement($attributes);
        $announcement->user()->associate($user);
        $announcement->save();

        return $announcement;
    }

    /**
     * Update an announcement.
     *
     * @param Announcement $announcement
     * @param array $attributes
     * @param User|null $user
     * @return Announcement
     */
    public function update(Announcement $announcement, array $attributes, User $user = null)
    {
        if ($user !== null) {
            $announcement->user()->associate($user);
        }

        $announcement->update($attributes);

        return $announcement;
    }

    /**
     * Delete an announcement.
     *
     * @param Announcement $announcement
     * @return void
     * @throws \Exception
     */
    public function delete(Announcement $announcement)
    {
        $announcement->delete();
    }

    /**
     * Get all model instances the user can view.
     *
     * @param User $user
     * @return \App\Models\Announcement[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getViewableBy(User $user)
    {
        return $user->can('view', Announcement::class)
            ? $this->repository->getAll()
            : $this->repository->getPublished();
    }
}
