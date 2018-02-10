<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Announcement;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnnouncementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the announcement.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Announcement  $announcement
     * @return mixed
     */
    public function view(User $user, Announcement $announcement)
    {
        $standardUser = $announcement->isPublished();
        $elevatedUser = $this->update($user, $announcement);

        return $standardUser || $elevatedUser;
    }

    /**
     * Determine whether the user can create announcements.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('announcements.create');
    }

    /**
     * Determine whether the user can update the announcement.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Announcement  $announcement
     * @return mixed
     */
    public function update(User $user, Announcement $announcement)
    {
        return $user->hasPermission('announcements.update');
    }

    /**
     * Determine whether the user can delete the announcement.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Announcement  $announcement
     * @return mixed
     */
    public function delete(User $user, Announcement $announcement)
    {
        return $user->hasPermission('announcements.delete');
    }
}
