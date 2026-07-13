<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\Patient;
use App\Models\RendezVous;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            ['ALAOUI', 'ALI', '0615141312', 'ali.alaoui@email.com', '1998-02-12', 'homme', 'O+', 'externe', 'Traitement antérieur pour hypertension', '123 Rue Principale, Casablanca', 'Fatima Alaoui', '0612345678', null, ['Pénicilline'], ['Hypertension'], null],
            ['MARTIN', 'MARIE', '0623456789', 'marie.martin@email.com', '1985-05-20', 'femme', 'A+', 'interne', 'Diabète type 2', '45 Avenue des Champs, Paris', 'Pierre Martin', '0623456789', null, [], [], ['Diabète type 2']],
            ['DUBOIS', 'JEAN', '0634567890', 'jean.dubois@email.com', '1975-11-08', 'homme', 'B-', 'externe', 'Allergie aux antibiotiques', '78 Boulevard de la Liberté, Lyon', 'Françoise Dubois', '0634567890', null, ['Antibiotiques', 'Aspirine'], [], null],
            ['BERNARD', 'SOPHIE', '0645678901', 'sophie.bernard@email.com', '1990-03-15', 'femme', 'AB+', 'externe', 'Aucun traitement particulier', '12 Rue de la Paix, Marseille', 'Luc Bernard', '0645678901', null, [], [], null],
            ['PETIT', 'PIERRE', '0656789012', 'pierre.petit@email.com', '1982-07-22', 'homme', 'O-', 'interne', 'Traitement cardiaque en cours', '56 Avenue du Soleil, Nice', 'Marie Petit', '0656789012', null, [], ['Hypertension', 'Cardiopathie'], null],
        ];

        $createdPatients = [];
        foreach ($patients as $data) {
            $createdPatients[] = Patient::firstOrCreate(
                ['email' => $data[3]],
                [
                    'nom' => $data[0],
                    'prenom' => $data[1],
                    'telephone' => $data[2],
                    'date_naissance' => $data[4],
                    'sexe' => $data[5],
                    'groupe_sanguin' => $data[6],
                    'statut_interne_externe' => $data[7],
                    'traitement_passe' => $data[8],
                    'adresse' => $data[9],
                    'contact_urgence_nom' => $data[10],
                    'contact_urgence_telephone' => $data[11],
                    'photo' => $data[12],
                    'allergies' => $data[13],
                    'antecedents' => $data[14],
                    'maladies_chroniques' => $data[15],
                ]
            );
        }

        $rendezVous = [
            [0, '2026-06-15 09:00:00', 'Consultation de routine', 'planifie'],
            [1, '2026-06-16 10:30:00', 'Suivi diabète', 'confirme'],
            [2, '2026-06-17 14:00:00', 'Contrôle allergie', 'planifie'],
            [3, '2026-06-18 11:15:00', 'Consultation générale', 'planifie'],
            [4, '2026-06-19 15:30:00', 'Suivi cardiaque', 'confirme'],
        ];

        foreach ($rendezVous as $item) {
            RendezVous::firstOrCreate(
                ['patient_id' => $createdPatients[$item[0]]->id, 'date_heure' => $item[1], 'motif' => $item[2]],
                ['statut' => $item[3]]
            );
        }

        $consultations = [
            [0, '2026-05-10 09:30:00', 'Consultation de routine', 'Patient en bonne santé générale', 'Repos et hydratation', 50.00, 'Paracétamol 500mg - 3 fois par jour'],
            [0, '2026-04-15 10:00:00', 'Mal de tête persistant', 'Céphalées de tension', 'Antalgiques et repos', 75.00, 'Ibuprofène 400mg - 2 fois par jour'],
            [0, '2026-03-20 14:30:00', 'Contrôle général', 'Bilan satisfaisant', 'Continuer hygiène de vie', 60.00, 'Vitamines C - 1 comprimé par jour'],
            [1, '2026-05-05 11:00:00', 'Suivi diabète', 'Glycémie stable', 'Continuer traitement', 80.00, 'Metformine 850mg - 2 fois par jour'],
            [2, '2026-04-28 15:15:00', 'Réaction allergique', 'Allergie alimentaire', 'Éviter allergènes', 90.00, 'Antihistaminique - selon besoin'],
            [3, '2026-05-01 09:45:00', 'Consultation préventive', 'Aucun problème détecté', 'Maintenir activité physique', 55.00, 'Aucune prescription'],
            [4, '2026-04-10 16:00:00', 'Suivi cardiaque', 'Tension artérielle élevée', 'Ajustement traitement', 100.00, 'Amlodipine 5mg - 1 fois par jour'],
        ];

        foreach ($consultations as $item) {
            Consultation::create([
                'patient_id' => $createdPatients[$item[0]]->id,
                'date_consultation' => $item[1],
                'motif' => $item[2],
                'diagnostic' => $item[3],
                'traitement' => $item[4],
                'prix' => $item[5],
                'ordonnance' => $item[6],
            ]);
        }
    }
}
