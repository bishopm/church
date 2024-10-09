<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\User;

class IndividualPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Individual');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Individual $individual): bool
    {
        return $user->checkPermissionTo('view Individual');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Individual');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Individual $individual): bool
    {
        return $user->checkPermissionTo('update Individual');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Individual $individual): bool
    {
        return $user->checkPermissionTo('delete Individual');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Individual');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Individual $individual): bool
    {
        return $user->checkPermissionTo('restore Individual');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Individual');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Individual $individual): bool
    {
        return $user->checkPermissionTo('replicate Individual');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Individual');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Individual $individual): bool
    {
        return $user->checkPermissionTo('force-delete Individual');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Individual');
    }
}
