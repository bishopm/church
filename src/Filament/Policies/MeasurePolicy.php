<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Measure;
use Bishopm\Church\Models\User;

class MeasurePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Measure');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Measure $measure): bool
    {
        return $user->checkPermissionTo('view Measure');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Measure');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Measure $measure): bool
    {
        return $user->checkPermissionTo('update Measure');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Measure $measure): bool
    {
        return $user->checkPermissionTo('delete Measure');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Measure');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Measure $measure): bool
    {
        return $user->checkPermissionTo('restore Measure');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Measure');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Measure $measure): bool
    {
        return $user->checkPermissionTo('replicate Measure');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Measure');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Measure $measure): bool
    {
        return $user->checkPermissionTo('force-delete Measure');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Measure');
    }
}
