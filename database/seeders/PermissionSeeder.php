<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Administration
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage roles']);
        Permission::firstOrCreate(['name' => 'view all records']);

        // Patient management
        Permission::firstOrCreate(['name' => 'view patients']);
        Permission::firstOrCreate(['name' => 'create patient']);
        Permission::firstOrCreate(['name' => 'edit patient']);
        Permission::firstOrCreate(['name' => 'search patients']);

        // Consultations
        Permission::firstOrCreate(['name' => 'view consultations']);
        Permission::firstOrCreate(['name' => 'create consultation']);
        Permission::firstOrCreate(['name' => 'edit consultation']);
        Permission::firstOrCreate(['name' => 'delete consultation']);
        Permission::firstOrCreate(['name' => 'view consultation details']);

        // Appointments
        Permission::firstOrCreate(['name' => 'view appointments']);
        Permission::firstOrCreate(['name' => 'create appointment']);
        Permission::firstOrCreate(['name' => 'edit appointment']);
        Permission::firstOrCreate(['name' => 'delete appointment']);
        Permission::firstOrCreate(['name' => 'view appointment details']);

        // Prescriptions
        Permission::firstOrCreate(['name' => 'view prescriptions']);
        Permission::firstOrCreate(['name' => 'create prescription']);
        Permission::firstOrCreate(['name' => 'edit prescription']);
        Permission::firstOrCreate(['name' => 'dispense medication']);

        // Laboratory
        Permission::firstOrCreate(['name' => 'view lab requests']);
        Permission::firstOrCreate(['name' => 'create lab request']);
        Permission::firstOrCreate(['name' => 'enter lab results']);
        Permission::firstOrCreate(['name' => 'validate lab results']);
        Permission::firstOrCreate(['name' => 'view lab results']);

        // Hospitalizations
        Permission::firstOrCreate(['name' => 'view hospitalizations']);
        Permission::firstOrCreate(['name' => 'create hospitalization']);
        Permission::firstOrCreate(['name' => 'edit hospitalization']);
        Permission::firstOrCreate(['name' => 'discharge patient']);

        // Nursing
        Permission::firstOrCreate(['name' => 'record vital signs']);
        Permission::firstOrCreate(['name' => 'update nursing observations']);

        // PDF Exports
        Permission::firstOrCreate(['name' => 'export medical record']);
        Permission::firstOrCreate(['name' => 'export medical summary']);
        Permission::firstOrCreate(['name' => 'export prescription']);
        Permission::firstOrCreate(['name' => 'export consultation report']);
        Permission::firstOrCreate(['name' => 'export hospitalization report']);
        Permission::firstOrCreate(['name' => 'export laboratory report']);
        Permission::firstOrCreate(['name' => 'export prescription history']);
        Permission::firstOrCreate(['name' => 'export patient audit']);
    }
}