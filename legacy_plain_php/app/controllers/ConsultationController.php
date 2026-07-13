<?php

class ConsultationController {
    private $db;

    public function __construct() {
        $this->db = db();
    }

    public function create() {
        $patientId = (int) ($_GET['patient_id'] ?? 0);
        $patient = $this->getPatient($patientId);
        $patients = $this->getPatients();
        $selectedPatientId = $patient ? (int) $patient['id'] : 0;

        include __DIR__ . '/../views/consultations/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            redirect('medical-files?error=csrf');
        }

        $patientId = (int) ($_POST['patient_id'] ?? 0);
        $dateConsultation = $_POST['date_consultation'] ?? date('Y-m-d H:i:s');
        $motif = trim($_POST['motif'] ?? '');
        $diagnostic = trim($_POST['diagnostic'] ?? '');
        $traitement = trim($_POST['traitement'] ?? '');
        $prix = (float) ($_POST['prix'] ?? 0);
        $ordonnance = trim($_POST['ordonnance'] ?? '');

        if (!$patientId || !$dateConsultation || !$motif || $prix <= 0) {
            $patient = $this->getPatient($patientId);
            $patients = $this->getPatients();
            $error = 'Veuillez remplir les champs obligatoires.';
            include __DIR__ . '/../views/consultations/create.php';
            return;
        }

        $stmt = $this->db->prepare("
            INSERT INTO consultations (patient_id, date_consultation, motif, diagnostic, traitement, prix, ordonnance)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$patientId, $dateConsultation, $motif, $diagnostic, $traitement, $prix, $ordonnance]);

        redirect('medical-files/view?id=' . $patientId . '&success=1');
    }

    private function getPatients() {
        $stmt = $this->db->query("SELECT id, nom, prenom FROM patients ORDER BY nom, prenom");
        return $stmt->fetchAll();
    }

    private function getPatient($id) {
        if (!$id) {
            return null;
        }

        $stmt = $this->db->prepare("SELECT * FROM patients WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
