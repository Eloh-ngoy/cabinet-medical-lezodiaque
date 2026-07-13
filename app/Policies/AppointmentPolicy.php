<?php

namespace App\Policies;

use App\Models\RendezVous;
use App\Models\User;

class AppointmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view appointments');
    }

    public function view(User $user, RendezVous $appointment): bool
    {
        return $user->can('view appointment details');
    }

    public function create(User $user): bool
    {
        return $user->can('create appointment');
    }

    public function update(User $user, RendezVous $appointment): bool
    {
        return $user->can('edit appointment');
    }

    public function delete(User $user, RendezVous $appointment): bool
    {
        return $user->can('delete appointment');
    }
}
