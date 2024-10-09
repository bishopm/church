<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Diaryentry;
use Bishopm\Church\Models\User;

class DiaryentryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Diaryentry');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Diaryentry $diaryentry): bool
    {
        return $user->checkPermissionTo('view Diaryentry');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Diaryentry');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Diaryentry $diaryentry): bool
    {
        return $user->checkPermissionTo('update Diaryentry');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Diaryentry $diaryentry): bool
    {
        return $user->checkPermissionTo('delete Diaryentry');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Diaryentry');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Diaryentry $diaryentry): bool
    {
        return $user->checkPermissionTo('restore Diaryentry');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Diaryentry');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Diaryentry $diaryentry): bool
    {
        return $user->checkPermissionTo('replicate Diaryentry');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Diaryentry');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Diaryentry $diaryentry): bool
    {
        return $user->checkPermissionTo('force-delete Diaryentry');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Diaryentry');
    }
}
