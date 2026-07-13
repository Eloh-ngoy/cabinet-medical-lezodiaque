<?php
require_once __DIR__ . '/../config/config.php';

try {
    if (DB_TYPE === 'mysql') {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        $pdo->exec('USE `' . DB_NAME . '`');
    } else {
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    // Table des utilisateurs (administrateur/docteur)
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        role VARCHAR(20) DEFAULT 'doctor',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Table des patients
    $pdo->exec("CREATE TABLE IF NOT EXISTS patients (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(50) NOT NULL,
        prenom VARCHAR(50) NOT NULL,
        telephone VARCHAR(20) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        date_naissance DATE NOT NULL,
        sexe VARCHAR(10) CHECK(sexe IN ('homme', 'femme')) NOT NULL,
        groupe_sanguin VARCHAR(5),
        statut_interne_externe VARCHAR(10) CHECK(statut_interne_externe IN ('interne', 'externe')) NOT NULL,
        traitement_passe TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Table des lits
    $pdo->exec("CREATE TABLE IF NOT EXISTS beds (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        bed_number INT UNIQUE NOT NULL,
        bed_type VARCHAR(30) DEFAULT 'standard',
        room_number INT,
        is_available BOOLEAN DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Table des hospitalisations
    $pdo->exec("CREATE TABLE IF NOT EXISTS hospitalizations (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        patient_id INT UNSIGNED NOT NULL,
        bed_id INT UNSIGNED NOT NULL,
        admission_date DATETIME NOT NULL,
        expected_duration INT,
        discharge_date DATETIME,
        status VARCHAR(20) DEFAULT 'active',
        admission_reason TEXT,
        discharge_notes TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
        FOREIGN KEY (bed_id) REFERENCES beds(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Table des rendez-vous
    $pdo->exec("CREATE TABLE IF NOT EXISTS rendez_vouses (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        patient_id INT UNSIGNED NOT NULL,
        date_heure DATETIME NOT NULL,
        motif TEXT NOT NULL,
        statut VARCHAR(20) CHECK(statut IN ('planifie', 'confirme', 'annule', 'termine')) DEFAULT 'planifie',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Table des consultations
    $pdo->exec("CREATE TABLE IF NOT EXISTS consultations (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        patient_id INT UNSIGNED NOT NULL,
        date_consultation DATETIME NOT NULL,
        motif TEXT NOT NULL,
        diagnostic TEXT,
        traitement TEXT,
        prix DECIMAL(10,2) NOT NULL,
        ordonnance TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Table des posologies (traitements pour patients hospitalisés)
    $pdo->exec("CREATE TABLE IF NOT EXISTS posologies (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        hospitalization_id INT UNSIGNED NOT NULL,
        medication_name VARCHAR(100) NOT NULL,
        dosage VARCHAR(50) NOT NULL,
        frequency VARCHAR(50) NOT NULL,
        duration VARCHAR(50),
        instructions TEXT,
        start_date DATE NOT NULL,
        end_date DATE,
        status VARCHAR(20) DEFAULT 'active',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (hospitalization_id) REFERENCES hospitalizations(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Insérer les lits (15 lits au total)
    $stmt = $pdo->prepare("INSERT IGNORE INTO beds (bed_number, bed_type, room_number, is_available) VALUES (?, ?, ?, 1)");
    for ($i = 1; $i <= TOTAL_BEDS; $i++) {
        $room = ceil($i / 3);
        $type = ($i <= 5) ? 'standard' : (($i <= 10) ? 'électrique' : 'soins intensifs');
        $stmt->execute([$i, $type, $room]);
    }
    
    // Mettre à jour le mot de passe administrateur sans dupliquer l'utilisateur
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO users (username, password, email, full_name, role)
        VALUES ('admin', ?, 'admin@lezodiaque.com', 'Administrateur LEZODIAQUE', 'admin')
        ON DUPLICATE KEY UPDATE
            email = VALUES(email),
            full_name = VALUES(full_name),
            role = VALUES(role),
            updated_at = CURRENT_TIMESTAMP
    ");
    $stmt->execute([$adminPassword]);
    
    echo "Base de données Laragon MySQL configurée avec succès!\n";
    echo "Base: " . DB_NAME . "\n";
    echo "Utilisateur par défaut: admin / admin123\n";
    
} catch (PDOException $e) {
    echo "Erreur de configuration de la base de données: " . $e->getMessage() . "\n";
    exit(1);
}

