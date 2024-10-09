<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Setitem;
use Bishopm\Church\Models\User;

class SetitemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Setitem');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Setitem $setitem): bool
    {
        return $user->checkPermissionTo('view Setitem');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Setitem');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Setitem $setitem): bool
    {
        return $user->checkPermissionTo('update Setitem');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Setitem $setitem): bool
    {
        return $user->checkPermissionTo('delete Setitem');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Setitem');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Setitem $setitem): bool
    {
        return $user->checkPermissionTo('restore Setitem');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Setitem');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Setitem $setitem): bool
    {
        return $user->checkPermissionTo('replicate Setitem');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Setitem');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Setitem $setitem): bool
    {
        return $user->checkPermissionTo('force-delete Setitem');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Setitem');
    }
}
