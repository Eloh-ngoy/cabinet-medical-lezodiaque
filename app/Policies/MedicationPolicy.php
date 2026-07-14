<?php

namespace App\Policies;

use App\Models\Medication;
use App\Models\User;

class MedicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view all records');
    }

    public function view(User $user, Medication $medication): bool
    {
        return $user->can('view all records');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Medication $medication): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Medication $medication): bool
    {
        return $user->hasRole('admin');
    }

    public function dispense(User $user, Medication $medication): bool
    {
        return $user->hasRole('admin');
    }

    public function restock(User $user, Medication $medication): bool
    {
        return $user->hasRole('admin');
    }
}