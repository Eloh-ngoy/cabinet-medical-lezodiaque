<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Hospitalization;
use App\Models\LaboratoryAnalysis;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\User;
use App\Policies\AppointmentPolicy;
use App\Policies\ConsultationPolicy;
use App\Policies\HospitalizationPolicy;
use App\Policies\LaboratoryAnalysisPolicy;
use App\Policies\MedicationPolicy;
use App\Policies\PatientPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Patient::class, PatientPolicy::class);
        Gate::policy(Consultation::class, ConsultationPolicy::class);
        Gate::policy(RendezVous::class, AppointmentPolicy::class);
        Gate::policy(Hospitalization::class, HospitalizationPolicy::class);
        Gate::policy(LaboratoryAnalysis::class, LaboratoryAnalysisPolicy::class);
        Gate::policy(Medication::class, MedicationPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
