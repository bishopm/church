<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Chord;
use Bishopm\Church\Models\User;

class ChordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Chord');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chord $chord): bool
    {
        return $user->checkPermissionTo('view Chord');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Chord');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Chord $chord): bool
    {
        return $user->checkPermissionTo('update Chord');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Chord $chord): bool
    {
        return $user->checkPermissionTo('delete Chord');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Chord');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Chord $chord): bool
    {
        return $user->checkPermissionTo('restore Chord');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Chord');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Chord $chord): bool
    {
        return $user->checkPermissionTo('replicate Chord');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Chord');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Chord $chord): bool
    {
        return $user->checkPermissionTo('force-delete Chord');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Chord');
    }
}
