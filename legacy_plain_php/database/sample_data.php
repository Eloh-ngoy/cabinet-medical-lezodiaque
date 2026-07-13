<?php
require_once __DIR__ . '/../config/config.php';

try {
    $pdo = db();
    
    // Insérer des patients de test
    $patients = [
        ['ALAOUI', 'ALI', '0615141312', 'ali.alaoui@email.com', '1998-02-12', 'homme', 'O+', 'externe', 'Traitement antérieur pour hypertension'],
        ['MARTIN', 'MARIE', '0623456789', 'marie.martin@email.com', '1985-05-20', 'femme', 'A+', 'interne', 'Diabète type 2'],
        ['DUBOIS', 'JEAN', '0634567890', 'jean.dubois@email.com', '1975-11-08', 'homme', 'B-', 'externe', 'Allergie aux antibiotiques'],
        ['BERNARD', 'SOPHIE', '0645678901', 'sophie.bernard@email.com', '1990-03-15', 'femme', 'AB+', 'externe', 'Aucun traitement particulier'],
        ['PETIT', 'PIERRE', '0656789012', 'pierre.petit@email.com', '1982-07-22', 'homme', 'O-', 'interne', 'Traitement cardiaque en cours']
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO patients (nom, prenom, telephone, email, date_naissance, sexe, groupe_sanguin, statut_interne_externe, traitement_passe) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($patients as $patient) {
        $stmt->execute($patient);
    }
    
    // Insérer des rendez-vous de test
    $rendezVous = [
        [1, '2026-06-15 09:00:00', 'Consultation de routine', 'planifie'],
        [2, '2026-06-16 10:30:00', 'Suivi diabète', 'confirme'],
        [3, '2026-06-17 14:00:00', 'Contrôle allergie', 'planifie'],
        [4, '2026-06-18 11:15:00', 'Consultation générale', 'planifie'],
        [5, '2026-06-19 15:30:00', 'Suivi cardiaque', 'confirme']
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO rendez_vouses (patient_id, date_heure, motif, statut) VALUES (?, ?, ?, ?)");
    
    foreach ($rendezVous as $rdv) {
        $stmt->execute($rdv);
    }
    
    // Insérer des consultations de test
    $consultations = [
        [1, '2026-05-10 09:30:00', 'Consultation de routine', 'Patient en bonne santé générale', 'Repos et hydratation', 50.00, 'Paracétamol 500mg - 3 fois par jour'],
        [1, '2026-04-15 10:00:00', 'Mal de tête persistant', 'Céphalées de tension', 'Antalgiques et repos', 75.00, 'Ibuprofène 400mg - 2 fois par jour'],
        [1, '2026-03-20 14:30:00', 'Contrôle général', 'Bilan satisfaisant', 'Continuer hygiène de vie', 60.00, 'Vitamines C - 1 comprimé par jour'],
        [2, '2026-05-05 11:00:00', 'Suivi diabète', 'Glycémie stable', 'Continuer traitement', 80.00, 'Metformine 850mg - 2 fois par jour'],
        [3, '2026-04-28 15:15:00', 'Réaction allergique', 'Allergie alimentaire', 'Éviter allergènes', 90.00, 'Antihistaminique - selon besoin'],
        [4, '2026-05-01 09:45:00', 'Consultation préventive', 'Aucun problème détecté', 'Maintenir activité physique', 55.00, 'Aucune prescription'],
        [5, '2026-04-10 16:00:00', 'Suivi cardiaque', 'Tension artérielle élevée', 'Ajustement traitement', 100.00, 'Amlodipine 5mg - 1 fois par jour']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO consultations (patient_id, date_consultation, motif, diagnostic, traitement, prix, ordonnance) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($consultations as $consultation) {
        $stmt->execute($consultation);
    }
    
    echo "Données de test insérées avec succès!\n";
    
} catch (PDOException $e) {
    echo "Erreur lors de l'insertion des données de test: " . $e->getMessage() . "\n";
}

