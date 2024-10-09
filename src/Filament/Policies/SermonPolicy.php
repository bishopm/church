<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Sermon;
use Bishopm\Church\Models\User;

class SermonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Sermon');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sermon $sermon): bool
    {
        return $user->checkPermissionTo('view Sermon');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Sermon');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sermon $sermon): bool
    {
        return $user->checkPermissionTo('update Sermon');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sermon $sermon): bool
    {
        return $user->checkPermissionTo('delete Sermon');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Sermon');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sermon $sermon): bool
    {
        return $user->checkPermissionTo('restore Sermon');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Sermon');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Sermon $sermon): bool
    {
        return $user->checkPermissionTo('replicate Sermon');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Sermon');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sermon $sermon): bool
    {
        return $user->checkPermissionTo('force-delete Sermon');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Sermon');
    }
}
