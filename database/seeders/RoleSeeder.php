<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'Directeur Général Médecin',
            'Médecin',
            'Infirmier',
            'Pharmacien',
            'Laborantin',
            'Réceptionniste',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Directeur Général Médecin — Super admin
        $directeur = Role::findByName('Directeur Général Médecin');
        $directeur->syncPermissions(Permission::all());

        // Médecin — Accès médical complet
        $medecin = Role::findByName('Médecin');
        $medecin->syncPermissions([
            'view patients', 'search patients',
            'view consultations', 'create consultation', 'edit consultation', 'view consultation details',
            'view prescriptions', 'create prescription', 'edit prescription',
            'view lab requests', 'create lab request', 'view lab results',
            'view hospitalizations', 'create hospitalization', 'edit hospitalization', 'discharge patient',
            'export medical record', 'export medical summary', 'export prescription',
            'export consultation report', 'export hospitalization report',
        ]);

        // Infirmier — Soins et suivi
        $infirmier = Role::findByName('Infirmier');
        $infirmier->syncPermissions([
            'view patients', 'search patients',
            'view consultations', 'view consultation details',
            'view hospitalizations',
            'record vital signs', 'update nursing observations',
            'export consultation report',
        ]);

        // Pharmacien — Gestion des médicaments et prescriptions
        $pharmacien = Role::findByName('Pharmacien');
        $pharmacien->syncPermissions([
            'view patients', 'search patients',
            'view prescriptions', 'dispense medication',
            'view consultations', 'view consultation details',
            'export prescription', 'export prescription history',
        ]);

        // Laborantin — Gestion des analyses
        $laborantin = Role::findByName('Laborantin');
        $laborantin->syncPermissions([
            'view patients', 'search patients',
            'view lab requests', 'create lab request', 'enter lab results',
            'validate lab results', 'view lab results',
            'export laboratory report',
        ]);

        // Réceptionniste — Gestion administrative
        $receptionniste = Role::findByName('Réceptionniste');
        $receptionniste->syncPermissions([
            'view patients', 'create patient', 'search patients',
            'view appointments', 'create appointment', 'view appointment details',
            'view hospitalizations', 'create hospitalization',
        ]);
    }
}
