<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any tasks.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view a task.
     */
    public function view(User $user, Task $task): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create tasks.
     */
    public function create(User $user): bool
    {
        return $user->isTeacher() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update a task.
     */
    public function update(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isTeacher()
            && $task->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete a task.
     */
    public function delete(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isTeacher()
            && $task->created_by === $user->id;
    }

    /**
     * Determine whether the user can restore a task.
     */
    public function restore(User $user, Task $task): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete a task.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->isAdmin();
    }
}