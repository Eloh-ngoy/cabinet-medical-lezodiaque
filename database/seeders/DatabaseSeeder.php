<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            AdminSeeder::class,
            BedSeeder::class,
            SampleDataSeeder::class,
            MedicationSeeder::class,
            LaboratoryAnalysisSeeder::class,
        ]);
    }
}
