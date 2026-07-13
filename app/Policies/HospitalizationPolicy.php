<?php

namespace App\Policies;

use App\Models\Hospitalization;
use App\Models\User;

class HospitalizationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view hospitalizations');
    }

    public function view(User $user, Hospitalization $hospitalization): bool
    {
        return $user->can('view hospitalizations');
    }

    public function create(User $user): bool
    {
        return $user->can('create hospitalization');
    }

    public function update(User $user, Hospitalization $hospitalization): bool
    {
        return $user->can('edit hospitalization');
    }

    public function delete(User $user, Hospitalization $hospitalization): bool
    {
        return $user->can('edit hospitalization');
    }

    public function discharge(User $user, Hospitalization $hospitalization): bool
    {
        return $user->can('discharge patient');
    }
}
