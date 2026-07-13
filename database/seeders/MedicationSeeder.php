<?php

namespace Database\Seeders;

use App\Models\Medication;
use Illuminate\Database\Seeder;

class MedicationSeeder extends Seeder
{
    public function run(): void
    {
        $medications = [
            ['Paracétamol 500mg', 'Paracétamol', 'Antalgique', 'boîte', 150, 30, 2.50, 'Antalgique et antipyrétique'],
            ['Ibuprofène 400mg', 'Ibuprofène', 'Anti-inflammatoire', 'boîte', 80, 20, 3.20, 'Anti-inflammatoire non stéroïdien'],
            ['Amoxicilline 500mg', 'Amoxicilline', 'Antibiotique', 'boîte', 60, 15, 5.80, 'Antibiotique pénicilline'],
            ['Metformine 850mg', 'Metformine', 'Diabète', 'boîte', 40, 10, 4.10, 'Antidiabétique oral'],
            ['Amlodipine 5mg', 'Amlodipine', 'Cardiovasculaire', 'boîte', 35, 10, 3.90, 'Inhibiteur calcique'],
            ['Salbutamol 100µg', 'Salbutamol', 'Autre', 'inhalateur', 20, 5, 8.50, 'Bronchodilatateur'],
            ['Omeprazole 20mg', 'Omeprazole', 'Gastro-intestinal', 'boîte', 70, 15, 3.50, 'Inhibiteur de pompe à protons'],
            ['Cétirizine 10mg', 'Cétirizine', 'Antihistaminique', 'boîte', 50, 15, 2.80, 'Antihistaminique H1'],
            ['Vitamine D3 1000UI', 'Cholécalciférol', 'Vitamines', 'boîte', 100, 25, 4.50, 'Supplémentation vitamine D'],
            ['Aspirine 100mg', 'Acide acétylsalicylique', 'Cardiovasculaire', 'boîte', 8, 15, 1.90, 'Antiagrégant plaquettaire'],
            ['Spironolactone 25mg', 'Spironolactone', 'Cardiovasculaire', 'boîte', 30, 10, 3.40, 'Diurétique épargneur de potassium'],
            ['Loratadine 10mg', 'Loratadine', 'Antihistaminique', 'boîte', 45, 15, 2.60, 'Antihistaminique non sédatif'],
            ['Azithromycine 250mg', 'Azithromycine', 'Antibiotique', 'boîte', 25, 10, 7.20, 'Macrolide antibiotique'],
            ['Fer sulfaté 80mg', 'Sulfate de fer', 'Vitamines', 'boîte', 55, 15, 3.80, 'Supplémentation en fer'],
            ['Atorvastatine 20mg', 'Atorvastatine', 'Cardiovasculaire', 'boîte', 12, 10, 5.50, 'Statine hypolipidémiante'],
        ];

        foreach ($medications as $med) {
            Medication::firstOrCreate(
                ['name' => $med[0]],
                [
                    'generic_name' => $med[1],
                    'category' => $med[2],
                    'unit' => $med[3],
                    'stock_quantity' => $med[4],
                    'min_stock_threshold' => $med[5],
                    'unit_price' => $med[6],
                    'description' => $med[7],
                ]
            );
        }
    }
}
