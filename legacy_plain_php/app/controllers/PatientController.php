<?php

class PatientController {
    private $db;
    
    public function __construct() {
        $this->db = db();
    }
    
    public function index() {
        $search = $_GET['search'] ?? '';
        $patients = $this->searchPatients($search);
        
        include __DIR__ . '/../views/patients/index.php';
    }
    
    public function create() {
        include __DIR__ . '/../views/patients/create.php';
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf_token = $_POST['csrf_token'] ?? '';
            
            if (!verifyCSRFToken($csrf_token)) {
                $error = "Token de sécurité invalide";
                include __DIR__ . '/../views/patients/create.php';
                return;
            }
            
            $data = [
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'date_naissance' => $_POST['date_naissance'] ?? '',
                'sexe' => $_POST['sexe'] ?? '',
                'groupe_sanguin' => $_POST['groupe_sanguin'] ?? '',
                'statut_interne_externe' => $_POST['statut_interne_externe'] ?? 'externe',
                'traitement_passe' => $_POST['traitement_passe'] ?? ''
            ];
            
            try {
                $stmt = $this->db->prepare("
                    INSERT INTO patients (
                        nom, prenom, telephone, email, date_naissance, 
                        sexe, groupe_sanguin, statut_interne_externe, traitement_passe
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                $stmt->execute([
                    $data['nom'],
                    $data['prenom'],
                    $data['telephone'],
                    $data['email'],
                    $data['date_naissance'],
                    $data['sexe'],
                    $data['groupe_sanguin'],
                    $data['statut_interne_externe'],
                    $data['traitement_passe']
                ]);
                
                $patientId = $this->db->lastInsertId();
                
                // Si le patient est interne, assigner un lit
                if ($data['statut_interne_externe'] === 'interne') {
                    $this->assignBed($patientId);
                }
                
                header('Location: ' . url('patients?success=1'));
                exit;
                
            } catch (PDOException $e) {
                $error = "Erreur lors de l'enregistrement: " . $e->getMessage();
                include __DIR__ . '/../views/patients/create.php';
            }
        }
    }
    
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $patient = $this->getPatientById($id);
        
        if (!$patient) {
            header('Location: ' . url('patients?error=patient_not_found'));
            exit;
        }
        
        include __DIR__ . '/../views/patients/edit.php';
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $csrf_token = $_POST['csrf_token'] ?? '';
            
            if (!verifyCSRFToken($csrf_token)) {
                $error = "Token de sécurité invalide";
                $patient = $this->getPatientById($id);
                include __DIR__ . '/../views/patients/edit.php';
                return;
            }
            
            $data = [
                'nom' => trim($_POST['nom'] ?? ''),
                'prenom' => trim($_POST['prenom'] ?? ''),
                'telephone' => trim($_POST['telephone'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'date_naissance' => $_POST['date_naissance'] ?? '',
                'sexe' => $_POST['sexe'] ?? 'homme',
                'groupe_sanguin' => $_POST['groupe_sanguin'] ?? '',
                'statut_interne_externe' => $_POST['statut_interne_externe'] ?? 'externe',
                'traitement_passe' => $_POST['traitement_passe'] ?? ''
            ];
            
            try {
                $stmt = $this->db->prepare("
                    UPDATE patients SET 
                        nom = ?, prenom = ?, telephone = ?, email = ?, 
                        date_naissance = ?, sexe = ?, groupe_sanguin = ?, statut_interne_externe = ?, 
                        traitement_passe = ?, updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?
                ");
                
                $stmt->execute([
                    $data['nom'],
                    $data['prenom'],
                    $data['telephone'],
                    $data['email'],
                    $data['date_naissance'],
                    $data['sexe'],
                    $data['groupe_sanguin'],
                    $data['statut_interne_externe'],
                    $data['traitement_passe'],
                    $id
                ]);
                
                header('Location: ' . url('patients?success=updated'));
                exit;
                
            } catch (PDOException $e) {
                $error = "Erreur lors de la mise à jour: " . $e->getMessage();
                $patient = $this->getPatientById($id);
                include __DIR__ . '/../views/patients/edit.php';
            }
        }
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $csrf_token = $_POST['csrf_token'] ?? '';
            
            if (!verifyCSRFToken($csrf_token)) {
                header('Location: ' . url('patients?error=csrf'));
                exit;
            }
            
            try {
                $this->db->prepare("DELETE FROM patients WHERE id = ?")->execute([$id]);
                header('Location: ' . url('patients?success=deleted'));
                exit;
            } catch (PDOException $e) {
                header('Location: ' . url('patients?error=delete_failed'));
                exit;
            }
        }
    }
    
    private function searchPatients($search) {
        if (empty($search)) {
            $stmt = $this->db->query("SELECT * FROM patients ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        $stmt = $this->db->prepare("
            SELECT * FROM patients 
            WHERE nom LIKE ? 
               OR prenom LIKE ? 
               OR email LIKE ? 
               OR telephone LIKE ?
            ORDER BY created_at DESC
        ");
        
        $searchTerm = "%$search%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getPatientById($id) {
        $stmt = $this->db->prepare("SELECT * FROM patients WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    private function generatePatientCode() {
        $year = date('Y');
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM patients WHERE YEAR(created_at) = ?");
        $stmt->execute([$year]);
        $count = $stmt->fetch()['count'] + 1;
        
        return 'AA' . $year . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
    
    private function assignBed($patientId) {
        // Trouver un lit disponible
        $stmt = $this->db->query("SELECT * FROM beds WHERE is_available = 1 ORDER BY bed_number LIMIT 1");
        $bed = $stmt->fetch();
        
        if ($bed) {
            // Marquer le lit comme occupé
            $this->db->prepare("UPDATE beds SET is_available = 0 WHERE id = ?")->execute([$bed['id']]);
            
            // Créer l'hospitalisation
            $stmt = $this->db->prepare("
                INSERT INTO hospitalizations (patient_id, bed_id, admission_date, status, admission_reason) 
                VALUES (?, ?, ?, 'active', 'Admission automatique')
            ");
            $stmt->execute([$patientId, $bed['id'], date('Y-m-d H:i:s')]);
        }
    }
}

