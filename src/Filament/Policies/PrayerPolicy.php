<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Prayer;
use Bishopm\Church\Models\User;

class PrayerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Prayer');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Prayer $prayer): bool
    {
        return $user->checkPermissionTo('view Prayer');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Prayer');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Prayer $prayer): bool
    {
        return $user->checkPermissionTo('update Prayer');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Prayer $prayer): bool
    {
        return $user->checkPermissionTo('delete Prayer');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Prayer');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Prayer $prayer): bool
    {
        return $user->checkPermissionTo('restore Prayer');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Prayer');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Prayer $prayer): bool
    {
        return $user->checkPermissionTo('replicate Prayer');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Prayer');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Prayer $prayer): bool
    {
        return $user->checkPermissionTo('force-delete Prayer');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Prayer');
    }
}
