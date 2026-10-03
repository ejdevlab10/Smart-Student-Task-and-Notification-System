<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    /**
     * Determine whether the user can view any announcements.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the announcement.
     */
    public function view(User $user, Announcement $announcement): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create announcements.
     */
    public function create(User $user): bool
    {
        return $user->isTeacher() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the announcement.
     */
    public function update(User $user, Announcement $announcement): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the announcement.
     */
    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the announcement.
     */
    public function restore(User $user, Announcement $announcement): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the announcement.
     */
    public function forceDelete(User $user, Announcement $announcement): bool
    {
        return $user->isAdmin();
    }
}