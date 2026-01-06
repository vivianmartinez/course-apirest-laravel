<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $authUser): bool
    {
        return $authUser->hasAnyRole(['admin', 'manager'])|| $authUser->can('users.view');
    }

    public function view(User $authUser, User $targetUser): bool
    {
        return $authUser->hasAnyRole(['admin', 'manager'])
            || $authUser->can('users.view')
            || $authUser->id === $targetUser->id;
    }

    public function create(User $authUser): bool
    {
        return $authUser->hasRole('admin')
            || $authUser->can('users.create');
    }

    public function update(User $authUser, User $targetUser): bool
    {
        return $authUser->hasRole('admin')
            || $authUser->can('users.update')
            || $authUser->id === $targetUser->id;
    }

    public function delete(User $authUser, User $targetUser): bool
    {
        return ($authUser->hasRole('admin') || $authUser->can('users.delete'))
            && $authUser->id !== $targetUser->id; // evitar autodelete
    }

    public function restore(User $authUser, User $targetUser): bool
    {
        return $authUser->hasRole('admin');
    }

    public function forceDelete(User $authUser, User $targetUser): bool
    {
        return $authUser->hasRole('admin');
    }
}
