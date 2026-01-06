<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->hasAnyRole(['admin', 'manager', 'moderator']) || $authUser->can('tasks.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $authUser, Task $task): bool
    {
        return $authUser->hasAnyRole(['admin', 'manager', 'moderator'])
            || $authUser->can('tasks.view')
            || $authUser->id === $task->created_by
            || $authUser->id === $task->assigned_to;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasAnyRole(['admin', 'manager', 'moderator'])
            || $authUser->can('tasks.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authUser, Task $task): bool
    {
        return $authUser->hasAnyRole(['admin', 'manager', 'moderator'])
            || $authUser->can('tasks.update')
            || $authUser->id === $task->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authUser, Task $task): bool
    {
        return $authUser->hasAnyRole(['admin', 'manager', 'moderator'])
            || $authUser->can('tasks.delete')
            || $authUser->id === $task->created_by;
    }

    // /**
    //  * Determine whether the user can restore the model.
    //  */
    // public function restore(User $authUser, Task $task): bool
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $authUser, Task $task): bool
    {
        return $authUser->hasAnyRole(['admin', 'manager', 'moderator']);
    }
}
