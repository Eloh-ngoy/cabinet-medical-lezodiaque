<?php

namespace Database\Seeders;

use App\Models\LaboratoryAnalysis;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class LaboratoryAnalysisSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $admin = User::where('username', 'admin')->first();

        if ($patients->isEmpty() || !$admin) {
            return;
        }

        $analyses = [
            [0, 'Hémogramme (NFS)', 'Bilan sanguin complet pour vérification de routine', 'validee', 'Résultats normaux. Globules blancs: 6.5 G/L, Globules rouges: 4.8 T/L, Hémoglobine: 14.2 g/dL, Plaquettes: 250 G/L.'],
            [1, 'Glycémie', 'Contrôle glycémie pour suivi diabète', 'terminee', 'Glycémie à jeun: 1.45 g/L (légerement élevé). HbA1c: 7.2%.'],
            [2, 'Bilan hépatique', 'Vérification fonction hépatique suite allergie médicamenteuse', 'demandee', null],
            [3, 'Cholestérol', 'Bilan lipidique de routine', 'demandee', null],
            [4, 'Fer sérique', 'Vérification des niveaux de fer', 'validee', 'Fer sérique: 65 µg/dL (normal). Ferritine: 45 ng/mL.'],
        ];

        foreach ($analyses as $data) {
            $patient = $patients->get($data[0]);
            if (!$patient) continue;

            LaboratoryAnalysis::create([
                'patient_id' => $patient->id,
                'requested_by' => $admin->id,
                'analysis_type' => $data[1],
                'description' => $data[2],
                'status' => $data[3],
                'results' => $data[4],
                'validated_by' => $data[3] === 'validee' ? $admin->id : null,
                'validated_at' => $data[3] === 'validee' ? now()->subDays(3) : null,
                'requested_at' => now()->subDays(7),
                'completed_at' => in_array($data[3], ['terminee', 'validee']) ? now()->subDays(5) : null,
            ]);
        }
    }
}
