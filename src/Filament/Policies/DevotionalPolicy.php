<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Devotional;
use Bishopm\Church\Models\User;

class DevotionalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Devotional');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Devotional $devotional): bool
    {
        return $user->checkPermissionTo('view Devotional');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Devotional');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Devotional $devotional): bool
    {
        return $user->checkPermissionTo('update Devotional');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Devotional $devotional): bool
    {
        return $user->checkPermissionTo('delete Devotional');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Devotional');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Devotional $devotional): bool
    {
        return $user->checkPermissionTo('restore Devotional');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Devotional');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Devotional $devotional): bool
    {
        return $user->checkPermissionTo('replicate Devotional');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Devotional');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Devotional $devotional): bool
    {
        return $user->checkPermissionTo('force-delete Devotional');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Devotional');
    }
}
