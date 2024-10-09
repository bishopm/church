<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Maintenancetask;
use Bishopm\Church\Models\User;

class MaintenancetaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Maintenancetask');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Maintenancetask $maintenancetask): bool
    {
        return $user->checkPermissionTo('view Maintenancetask');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Maintenancetask');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Maintenancetask $maintenancetask): bool
    {
        return $user->checkPermissionTo('update Maintenancetask');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Maintenancetask $maintenancetask): bool
    {
        return $user->checkPermissionTo('delete Maintenancetask');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Maintenancetask');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Maintenancetask $maintenancetask): bool
    {
        return $user->checkPermissionTo('restore Maintenancetask');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Maintenancetask');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Maintenancetask $maintenancetask): bool
    {
        return $user->checkPermissionTo('replicate Maintenancetask');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Maintenancetask');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Maintenancetask $maintenancetask): bool
    {
        return $user->checkPermissionTo('force-delete Maintenancetask');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Maintenancetask');
    }
}
