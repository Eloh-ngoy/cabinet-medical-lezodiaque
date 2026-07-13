<?php

class AppointmentController {
    private $db;

    public function __construct() {
        $this->db = db();
    }

    public function index() {
        $search = trim($_GET['search'] ?? '');
        $appointments = $this->getAppointments($search);
        $patients = $this->getPatients();

        include __DIR__ . '/../views/appointments/index.php';
    }

    public function create() {
        $patients = $this->getPatients();
        include __DIR__ . '/../views/appointments/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            redirect('appointments?error=csrf');
        }

        $patientId = (int) ($_POST['patient_id'] ?? 0);
        $dateHeure = $_POST['date_heure'] ?? '';
        $motif = trim($_POST['motif'] ?? '');
        $statut = $_POST['statut'] ?? 'planifie';

        if (!$patientId || !$dateHeure || !$motif || !in_array($statut, ['planifie', 'confirme', 'annule', 'termine'])) {
            $patients = $this->getPatients();
            $error = 'Veuillez remplir tous les champs obligatoires.';
            include __DIR__ . '/../views/appointments/create.php';
            return;
        }

        $stmt = $this->db->prepare("
            INSERT INTO rendez_vouses (patient_id, date_heure, motif, statut)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$patientId, $dateHeure, $motif, $statut]);

        redirect('appointments?success=1');
    }

    public function edit() {
        $id = (int) ($_GET['id'] ?? 0);
        $appointment = $this->getAppointment($id);
        $patients = $this->getPatients();

        if (!$appointment) {
            redirect('appointments?error=not_found');
        }

        include __DIR__ . '/../views/appointments/create.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            redirect('appointments?error=csrf');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $patientId = (int) ($_POST['patient_id'] ?? 0);
        $dateHeure = $_POST['date_heure'] ?? '';
        $motif = trim($_POST['motif'] ?? '');
        $statut = $_POST['statut'] ?? 'planifie';

        if (!$id || !$patientId || !$dateHeure || !$motif || !in_array($statut, ['planifie', 'confirme', 'annule', 'termine'])) {
            $appointment = $this->getAppointment($id);
            $patients = $this->getPatients();
            $error = 'Veuillez remplir tous les champs obligatoires.';
            include __DIR__ . '/../views/appointments/create.php';
            return;
        }

        $stmt = $this->db->prepare("
            UPDATE rendez_vouses
            SET patient_id = ?, date_heure = ?, motif = ?, statut = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $stmt->execute([$patientId, $dateHeure, $motif, $statut, $id]);

        redirect('appointments?success=updated');
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            redirect('appointments?error=csrf');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $this->db->prepare('DELETE FROM rendez_vouses WHERE id = ?')->execute([$id]);

        redirect('appointments?success=deleted');
    }

    private function getAppointments($search = '') {
        if ($search === '') {
            $stmt = $this->db->query("
                SELECT r.*, p.nom, p.prenom
                FROM rendez_vouses r
                JOIN patients p ON p.id = r.patient_id
                ORDER BY r.date_heure ASC
            ");
            return $stmt->fetchAll();
        }

        $stmt = $this->db->prepare("
            SELECT r.*, p.nom, p.prenom
            FROM rendez_vouses r
            JOIN patients p ON p.id = r.patient_id
            WHERE r.motif LIKE ? OR p.nom LIKE ? OR p.prenom LIKE ?
            ORDER BY r.date_heure ASC
        ");
        $term = "%{$search}%";
        $stmt->execute([$term, $term, $term]);
        return $stmt->fetchAll();
    }

    private function getAppointment($id) {
        $stmt = $this->db->prepare("SELECT * FROM rendez_vouses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function getPatients() {
        $stmt = $this->db->query("SELECT id, nom, prenom FROM patients ORDER BY nom, prenom");
        return $stmt->fetchAll();
    }
}
