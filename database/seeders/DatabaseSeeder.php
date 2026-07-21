<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Création des rôles du système
        $roles = [
            'admin',
            'doctor',
            'nurse',
            'pharmacist',
            'laboratory',
            'receptionist',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
        }


        // Récupérer le rôle admin
        $adminRole = Role::where('name', 'admin')
            ->where('guard_name', 'web')
            ->first();


        // Récupérer les rôles
        $admin = Role::where('name', 'admin')->first();
        $doctor = Role::where('name', 'doctor')->first();
        $nurse = Role::where('name', 'nurse')->first();
        $pharmacist = Role::where('name', 'pharmacist')->first();
        $laboratory = Role::where('name', 'laboratory')->first();
        $receptionist = Role::where('name', 'receptionist')->first();


        // ADMIN : toutes les permissions
        if ($admin) {
            $admin->syncPermissions(Permission::all());
        }


        // Permissions de consultation uniquement
        $viewPermissions = [
            'view all records',

            // Patients
            'view patients',
            'search patients',

            // Consultations
            'view consultations',
            'view consultation details',

            // Rendez-vous
            'view appointments',
            'view appointment details',

            // Hospitalisations
            'view hospitalizations',

            // Laboratoire
            'view lab requests',
            'view lab results',

            // Pharmacie
            'view prescriptions',
        ];


        // Autres utilisateurs : lecture seulement
        foreach ([$doctor, $nurse, $pharmacist, $laboratory, $receptionist] as $role) {
            if ($role) {
                $role->syncPermissions(
                    Permission::whereIn('name', $viewPermissions)->get()
                );
            }
        }


        // Création du compte administrateur
        $user = User::firstOrCreate(
            [
                'username' => 'admin'
            ],
            [
                'email' => 'admin@lezodiaque.com',
                'full_name' => 'Dr. Directeur Général LEZODIAQUE',
                'matricule' => 'MED-001',
                'password' => Hash::make('admin123'),
                'must_change_password' => false,
                'role' => 'doctor',
            ]
        );

        // Attribuer le rôle admin à l'utilisateur
        $user->syncRoles(['admin']);

        // Créer les lits
        $this->call([
            BedSeeder::class,
        ]);
    }
}