<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Groupmember;
use Bishopm\Church\Models\User;

class GroupmemberPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Groupmember');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Groupmember $groupmember): bool
    {
        return $user->checkPermissionTo('view Groupmember');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Groupmember');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Groupmember $groupmember): bool
    {
        return $user->checkPermissionTo('update Groupmember');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Groupmember $groupmember): bool
    {
        return $user->checkPermissionTo('delete Groupmember');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Groupmember');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Groupmember $groupmember): bool
    {
        return $user->checkPermissionTo('restore Groupmember');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Groupmember');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Groupmember $groupmember): bool
    {
        return $user->checkPermissionTo('replicate Groupmember');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Groupmember');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Groupmember $groupmember): bool
    {
        return $user->checkPermissionTo('force-delete Groupmember');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Groupmember');
    }
}
