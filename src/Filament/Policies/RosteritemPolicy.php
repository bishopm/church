<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Rosteritem;
use Bishopm\Church\Models\User;

class RosteritemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Rosteritem');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Rosteritem $rosteritem): bool
    {
        return $user->checkPermissionTo('view Rosteritem');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Rosteritem');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rosteritem $rosteritem): bool
    {
        return $user->checkPermissionTo('update Rosteritem');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rosteritem $rosteritem): bool
    {
        return $user->checkPermissionTo('delete Rosteritem');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Rosteritem');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Rosteritem $rosteritem): bool
    {
        return $user->checkPermissionTo('restore Rosteritem');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Rosteritem');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Rosteritem $rosteritem): bool
    {
        return $user->checkPermissionTo('replicate Rosteritem');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Rosteritem');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Rosteritem $rosteritem): bool
    {
        return $user->checkPermissionTo('force-delete Rosteritem');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Rosteritem');
    }
}
