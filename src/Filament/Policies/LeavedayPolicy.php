<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Leaveday;
use Bishopm\Church\Models\User;

class LeavedayPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Leaveday');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Leaveday $leaveday): bool
    {
        return $user->checkPermissionTo('view Leaveday');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Leaveday');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Leaveday $leaveday): bool
    {
        return $user->checkPermissionTo('update Leaveday');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Leaveday $leaveday): bool
    {
        return $user->checkPermissionTo('delete Leaveday');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Leaveday');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Leaveday $leaveday): bool
    {
        return $user->checkPermissionTo('restore Leaveday');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Leaveday');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Leaveday $leaveday): bool
    {
        return $user->checkPermissionTo('replicate Leaveday');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Leaveday');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Leaveday $leaveday): bool
    {
        return $user->checkPermissionTo('force-delete Leaveday');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Leaveday');
    }
}
