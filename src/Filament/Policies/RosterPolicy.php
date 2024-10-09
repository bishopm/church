<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Roster;
use Bishopm\Church\Models\User;

class RosterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Roster');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Roster $roster): bool
    {
        return $user->checkPermissionTo('view Roster');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Roster');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Roster $roster): bool
    {
        return $user->checkPermissionTo('update Roster');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Roster $roster): bool
    {
        return $user->checkPermissionTo('delete Roster');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Roster');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Roster $roster): bool
    {
        return $user->checkPermissionTo('restore Roster');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Roster');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Roster $roster): bool
    {
        return $user->checkPermissionTo('replicate Roster');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Roster');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Roster $roster): bool
    {
        return $user->checkPermissionTo('force-delete Roster');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Roster');
    }
}
