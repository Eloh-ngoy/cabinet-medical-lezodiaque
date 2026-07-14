<?php

namespace App\Policies;

use App\Models\LaboratoryAnalysis;
use App\Models\User;

class LaboratoryAnalysisPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view lab requests');
    }

    public function view(User $user, LaboratoryAnalysis $laboratoryAnalysis): bool
    {
        return $user->can('view lab results');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function enterResults(User $user, LaboratoryAnalysis $laboratoryAnalysis): bool
    {
        return $user->hasRole('admin');
    }

    public function validateResults(User $user, LaboratoryAnalysis $laboratoryAnalysis): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, LaboratoryAnalysis $laboratoryAnalysis): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, LaboratoryAnalysis $laboratoryAnalysis): bool
    {
        return $user->hasRole('admin');
    }
}