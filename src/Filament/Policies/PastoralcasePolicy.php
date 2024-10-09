<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Pastoralcase;
use Bishopm\Church\Models\User;

class PastoralcasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Pastoralcase');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pastoralcase $pastoralcase): bool
    {
        return $user->checkPermissionTo('view Pastoralcase');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Pastoralcase');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pastoralcase $pastoralcase): bool
    {
        return $user->checkPermissionTo('update Pastoralcase');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pastoralcase $pastoralcase): bool
    {
        return $user->checkPermissionTo('delete Pastoralcase');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Pastoralcase');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pastoralcase $pastoralcase): bool
    {
        return $user->checkPermissionTo('restore Pastoralcase');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Pastoralcase');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Pastoralcase $pastoralcase): bool
    {
        return $user->checkPermissionTo('replicate Pastoralcase');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Pastoralcase');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pastoralcase $pastoralcase): bool
    {
        return $user->checkPermissionTo('force-delete Pastoralcase');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Pastoralcase');
    }
}
