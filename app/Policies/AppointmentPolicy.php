<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return ! $user->admin;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        return ! $user->admin && ($user->branch_id === $appointment->branch_id || ($user->role->type === 'Administración' && $user->clinic_id === $appointment->clinic_id));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return ! $user->admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        return ! $user->admin && ($user->branch_id === $appointment->branch_id || ($user->role->type === 'Administración' && $user->clinic_id === $appointment->clinic_id));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return ! $user->admin && ($user->branch_id === $appointment->branch_id || ($user->role->type === 'Administración' && $user->clinic_id === $appointment->clinic_id));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return ! $user->admin && ($user->branch_id === $appointment->branch_id || ($user->role->type === 'Administración' && $user->clinic_id === $appointment->clinic_id));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return false;
    }
}
