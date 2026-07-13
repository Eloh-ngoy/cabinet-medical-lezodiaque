<?php

class HospitalizationController {
    private $db;

    public function __construct() {
        $this->db = db();
    }

    public function index() {
        $hospitalizations = $this->getHospitalizations();
        include __DIR__ . '/../views/hospitalizations/index.php';
    }

    public function create() {
        $patients = $this->getPatients();
        $beds = $this->getAvailableBeds();
        include __DIR__ . '/../views/hospitalizations/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            redirect('hospitalizations?error=csrf');
        }

        $patientId = (int) ($_POST['patient_id'] ?? 0);
        $bedId = (int) ($_POST['bed_id'] ?? 0);
        $admissionDate = $_POST['admission_date'] ?? date('Y-m-d H:i:s');
        $expectedDuration = (int) ($_POST['expected_duration'] ?? 0);
        $admissionReason = trim($_POST['admission_reason'] ?? '');

        if (!$patientId || !$bedId || !$admissionDate) {
            $patients = $this->getPatients();
            $beds = $this->getAvailableBeds();
            $error = 'Veuillez remplir tous les champs obligatoires.';
            include __DIR__ . '/../views/hospitalizations/create.php';
            return;
        }

        $bed = $this->getBed($bedId);
        if (!$bed || (int) $bed['is_available'] === 0) {
            $patients = $this->getPatients();
            $beds = $this->getAvailableBeds();
            $error = 'Le lit sélectionné n’est plus disponible.';
            include __DIR__ . '/../views/hospitalizations/create.php';
            return;
        }

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                INSERT INTO hospitalizations (patient_id, bed_id, admission_date, expected_duration, status, admission_reason)
                VALUES (?, ?, ?, ?, 'active', ?)
            ");
            $stmt->execute([$patientId, $bedId, $admissionDate, $expectedDuration ?: null, $admissionReason]);

            $this->db->prepare('UPDATE beds SET is_available = 0 WHERE id = ?')->execute([$bedId]);
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            $patients = $this->getPatients();
            $beds = $this->getAvailableBeds();
            $error = 'Erreur lors de l’hospitalisation: ' . $e->getMessage();
            include __DIR__ . '/../views/hospitalizations/create.php';
            return;
        }

        redirect('hospitalizations?success=1');
    }

    public function discharge() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            redirect('hospitalizations?error=csrf');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $notes = trim($_POST['discharge_notes'] ?? '');

        $hospitalization = $this->getHospitalization($id);
        if (!$hospitalization) {
            redirect('hospitalizations?error=not_found');
        }

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                UPDATE hospitalizations
                SET discharge_date = NOW(), status = 'termine', discharge_notes = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            $stmt->execute([$notes, $id]);
            $this->db->prepare('UPDATE beds SET is_available = 1 WHERE id = ?')->execute([$hospitalization['bed_id']]);
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            redirect('hospitalizations?error=discharge_failed');
        }

        redirect('hospitalizations?success=discharged');
    }

    private function getHospitalizations() {
        $stmt = $this->db->query("
            SELECT h.*, p.nom, p.prenom, b.bed_number, b.room_number, b.bed_type
            FROM hospitalizations h
            JOIN patients p ON p.id = h.patient_id
            JOIN beds b ON b.id = h.bed_id
            ORDER BY CASE WHEN h.status = 'active' THEN 0 ELSE 1 END, h.admission_date DESC
        ");
        return $stmt->fetchAll();
    }

    private function getHospitalization($id) {
        $stmt = $this->db->prepare("SELECT * FROM hospitalizations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function getPatients() {
        $stmt = $this->db->query("SELECT id, nom, prenom FROM patients ORDER BY nom, prenom");
        return $stmt->fetchAll();
    }

    private function getAvailableBeds() {
        $stmt = $this->db->query("SELECT * FROM beds WHERE is_available = 1 ORDER BY bed_number");
        return $stmt->fetchAll();
    }

    private function getBed($id) {
        $stmt = $this->db->prepare("SELECT * FROM beds WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
