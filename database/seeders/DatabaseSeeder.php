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


        // Donner toutes les permissions au rôle admin
        if ($adminRole) {
            $adminRole->syncPermissions(Permission::all());
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