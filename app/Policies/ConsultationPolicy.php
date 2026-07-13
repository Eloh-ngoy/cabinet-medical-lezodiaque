<?php

namespace App\Policies;

use App\Models\Consultation;
use App\Models\User;

class ConsultationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view consultations');
    }

    public function view(User $user, Consultation $consultation): bool
    {
        return $user->can('view consultation details');
    }

    public function create(User $user): bool
    {
        return $user->can('create consultation');
    }

    public function update(User $user, Consultation $consultation): bool
    {
        return $user->can('edit consultation');
    }

    public function delete(User $user, Consultation $consultation): bool
    {
        return $user->can('delete consultation');
    }
}
