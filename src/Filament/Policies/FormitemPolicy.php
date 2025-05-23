<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Formitem;
use Bishopm\Church\Models\User;

class FormitemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Formitem');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Formitem $formitem): bool
    {
        return $user->checkPermissionTo('view Formitem');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Formitem');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Formitem $formitem): bool
    {
        return $user->checkPermissionTo('update Formitem');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Formitem $formitem): bool
    {
        return $user->checkPermissionTo('delete Formitem');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Formitem');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Formitem $formitem): bool
    {
        return $user->checkPermissionTo('restore Formitem');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Formitem');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Formitem $formitem): bool
    {
        return $user->checkPermissionTo('replicate Formitem');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Formitem');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Formitem $formitem): bool
    {
        return $user->checkPermissionTo('force-delete Formitem');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Formitem');
    }
}
