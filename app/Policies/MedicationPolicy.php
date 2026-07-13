<?php

namespace App\Policies;

use App\Models\Medication;
use App\Models\User;

class MedicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('dispense medication');
    }

    public function view(User $user, Medication $medication): bool
    {
        return $user->can('dispense medication');
    }

    public function create(User $user): bool
    {
        return $user->can('manage users');
    }

    public function update(User $user, Medication $medication): bool
    {
        return $user->can('manage users');
    }

    public function delete(User $user, Medication $medication): bool
    {
        return $user->can('manage users');
    }

    public function dispense(User $user, Medication $medication): bool
    {
        return $user->can('dispense medication');
    }

    public function restock(User $user, Medication $medication): bool
    {
        return $user->can('dispense medication');
    }
}
