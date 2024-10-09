<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Anniversary;
use Bishopm\Church\Models\User;

class AnniversaryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Anniversary');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Anniversary $anniversary): bool
    {
        return $user->checkPermissionTo('view Anniversary');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Anniversary');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Anniversary $anniversary): bool
    {
        return $user->checkPermissionTo('update Anniversary');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Anniversary $anniversary): bool
    {
        return $user->checkPermissionTo('delete Anniversary');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Anniversary');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Anniversary $anniversary): bool
    {
        return $user->checkPermissionTo('restore Anniversary');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Anniversary');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Anniversary $anniversary): bool
    {
        return $user->checkPermissionTo('replicate Anniversary');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Anniversary');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Anniversary $anniversary): bool
    {
        return $user->checkPermissionTo('force-delete Anniversary');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Anniversary');
    }
}
