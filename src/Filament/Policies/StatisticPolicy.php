<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Statistic;
use Bishopm\Church\Models\User;

class StatisticPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Statistic');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Statistic $statistic): bool
    {
        return $user->checkPermissionTo('view Statistic');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Statistic');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Statistic $statistic): bool
    {
        return $user->checkPermissionTo('update Statistic');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Statistic $statistic): bool
    {
        return $user->checkPermissionTo('delete Statistic');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Statistic');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Statistic $statistic): bool
    {
        return $user->checkPermissionTo('restore Statistic');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Statistic');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Statistic $statistic): bool
    {
        return $user->checkPermissionTo('replicate Statistic');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Statistic');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Statistic $statistic): bool
    {
        return $user->checkPermissionTo('force-delete Statistic');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Statistic');
    }
}
