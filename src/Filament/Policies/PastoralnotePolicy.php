<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Pastoralnote;
use Bishopm\Church\Models\User;

class PastoralnotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Pastoralnote');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pastoralnote $pastoralnote): bool
    {
        return $user->checkPermissionTo('view Pastoralnote');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Pastoralnote');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pastoralnote $pastoralnote): bool
    {
        return $user->checkPermissionTo('update Pastoralnote');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pastoralnote $pastoralnote): bool
    {
        return $user->checkPermissionTo('delete Pastoralnote');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Pastoralnote');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pastoralnote $pastoralnote): bool
    {
        return $user->checkPermissionTo('restore Pastoralnote');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Pastoralnote');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Pastoralnote $pastoralnote): bool
    {
        return $user->checkPermissionTo('replicate Pastoralnote');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Pastoralnote');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pastoralnote $pastoralnote): bool
    {
        return $user->checkPermissionTo('force-delete Pastoralnote');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Pastoralnote');
    }
}
