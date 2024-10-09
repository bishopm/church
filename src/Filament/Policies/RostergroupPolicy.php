<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Rostergroup;
use Bishopm\Church\Models\User;

class RostergroupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Rostergroup');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Rostergroup $rostergroup): bool
    {
        return $user->checkPermissionTo('view Rostergroup');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Rostergroup');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rostergroup $rostergroup): bool
    {
        return $user->checkPermissionTo('update Rostergroup');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rostergroup $rostergroup): bool
    {
        return $user->checkPermissionTo('delete Rostergroup');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Rostergroup');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Rostergroup $rostergroup): bool
    {
        return $user->checkPermissionTo('restore Rostergroup');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Rostergroup');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Rostergroup $rostergroup): bool
    {
        return $user->checkPermissionTo('replicate Rostergroup');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Rostergroup');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Rostergroup $rostergroup): bool
    {
        return $user->checkPermissionTo('force-delete Rostergroup');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Rostergroup');
    }
}
