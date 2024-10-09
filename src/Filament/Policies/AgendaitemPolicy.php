<?php

namespace Bishopm\Church\Filament\Policies;

use Illuminate\Auth\Access\Response;
use Bishopm\Church\Models\Agendaitem;
use Bishopm\Church\Models\User;

class AgendaitemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Agendaitem');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Agendaitem $agendaitem): bool
    {
        return $user->checkPermissionTo('view Agendaitem');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Agendaitem');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Agendaitem $agendaitem): bool
    {
        return $user->checkPermissionTo('update Agendaitem');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Agendaitem $agendaitem): bool
    {
        return $user->checkPermissionTo('delete Agendaitem');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Agendaitem');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Agendaitem $agendaitem): bool
    {
        return $user->checkPermissionTo('restore Agendaitem');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Agendaitem');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Agendaitem $agendaitem): bool
    {
        return $user->checkPermissionTo('replicate Agendaitem');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Agendaitem');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Agendaitem $agendaitem): bool
    {
        return $user->checkPermissionTo('force-delete Agendaitem');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Agendaitem');
    }
}
