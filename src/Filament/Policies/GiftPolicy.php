<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Gift;
use Bishopm\Church\Models\User;

class GiftPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Gift');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Gift $gift): bool
    {
        return $user->checkPermissionTo('view Gift');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Gift');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Gift $gift): bool
    {
        return $user->checkPermissionTo('update Gift');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Gift $gift): bool
    {
        return $user->checkPermissionTo('delete Gift');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Gift');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Gift $gift): bool
    {
        return $user->checkPermissionTo('restore Gift');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Gift');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Gift $gift): bool
    {
        return $user->checkPermissionTo('replicate Gift');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Gift');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Gift $gift): bool
    {
        return $user->checkPermissionTo('force-delete Gift');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Gift');
    }
}
