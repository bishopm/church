<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Pastor;
use Bishopm\Church\Models\User;

class PastorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Pastor');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pastor $pastor): bool
    {
        return $user->checkPermissionTo('view Pastor');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Pastor');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pastor $pastor): bool
    {
        return $user->checkPermissionTo('update Pastor');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pastor $pastor): bool
    {
        return $user->checkPermissionTo('delete Pastor');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Pastor');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pastor $pastor): bool
    {
        return $user->checkPermissionTo('restore Pastor');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Pastor');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Pastor $pastor): bool
    {
        return $user->checkPermissionTo('replicate Pastor');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Pastor');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pastor $pastor): bool
    {
        return $user->checkPermissionTo('force-delete Pastor');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Pastor');
    }
}
