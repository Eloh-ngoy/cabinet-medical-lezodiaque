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
        return $user->can('create lab request');
    }

    public function enterResults(User $user, LaboratoryAnalysis $laboratoryAnalysis): bool
    {
        return $user->can('enter lab results');
    }

    public function validateResults(User $user, LaboratoryAnalysis $laboratoryAnalysis): bool
    {
        return $user->can('validate lab results');
    }

    public function update(User $user, LaboratoryAnalysis $laboratoryAnalysis): bool
    {
        return $user->can('enter lab results');
    }
}
