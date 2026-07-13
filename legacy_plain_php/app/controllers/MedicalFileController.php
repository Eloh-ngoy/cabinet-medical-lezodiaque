<?php

if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}

class MedicalFileController {
    private $db;
    
    public function __construct() {
        $this->db = db();
    }
    
    public function index() {
        $patients = $this->getAllPatients();
        include __DIR__ . '/../views/medical_files/index.php';
    }
    
    public function view() {
        $id = $_GET['id'] ?? 0;
        $patient = $this->getPatientById($id);
        
        if (!$patient) {
            redirect('medical-files?error=patient_not_found');
        }
        
        $consultations = $this->getPatientConsultations($id);
        $rendezVous = $this->getPatientRendezVous($id);
        $stats = $this->getPatientStats($id);
        
        include __DIR__ . '/../views/medical_files/view.php';
    }
    
    public function exportPDF() {
        $id = $_GET['id'] ?? 0;
        $patient = $this->getPatientById($id);
        
        if (!$patient) {
            redirect('medical-files?error=patient_not_found');
        }
        
        $consultations = $this->getPatientConsultations($id);
        $rendezVous = $this->getPatientRendezVous($id);
        $stats = $this->getPatientStats($id);
        
        $patientCode = $this->generatePatientCode($id);
        
        $html = $this->generatePDFContent($patient, $consultations, $rendezVous, $stats, $patientCode);
        
        // Utiliser une bibliothèque PDF simple
        $this->generateSimplePDF($html, $patient['nom'] . '_' . $patient['prenom'] . '_dossier_medical.pdf');
    }
    
    private function getAllPatients() {
        $stmt = $this->db->query("SELECT * FROM patients ORDER BY nom, prenom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getPatientById($id) {
        $stmt = $this->db->prepare("SELECT * FROM patients WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function generatePatientCode($id) {
        return 'AA' . date('Y') . str_pad($id, 4, '0', STR_PAD_LEFT);
    }

    private function getPatientConsultations($patientId) {
        $stmt = $this->db->prepare("
            SELECT * FROM consultations 
            WHERE patient_id = ? 
            ORDER BY date_consultation DESC
        ");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getPatientRendezVous($patientId) {
        $stmt = $this->db->prepare("
            SELECT * FROM rendez_vouses 
            WHERE patient_id = ? 
            ORDER BY date_heure DESC
        ");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getPatientStats($patientId) {
        $stats = [];
        
        // Nombre de consultations
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM consultations WHERE patient_id = ?");
        $stmt->execute([$patientId]);
        $stats['consultations'] = $stmt->fetch()['count'];
        
        // Nombre de rendez-vous
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM rendez_vouses WHERE patient_id = ?");
        $stmt->execute([$patientId]);
        $stats['rendez_vous'] = $stmt->fetch()['count'];
        
        // Nombre d'ordonnances
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM consultations WHERE patient_id = ? AND ordonnance IS NOT NULL AND ordonnance != ''");
        $stmt->execute([$patientId]);
        $stats['ordonnances'] = $stmt->fetch()['count'];
        
        // Total des frais payés
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(prix), 0) as total FROM consultations WHERE patient_id = ?");
        $stmt->execute([$patientId]);
        $stats['frais_payes'] = $stmt->fetch()['total'];
        
        return $stats;
    }
    
    private function generatePDFContent($patient, $consultations, $rendezVous, $stats, $patientCode) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Dossier Médical - ' . htmlspecialchars($patient['nom'] . ' ' . $patient['prenom']) . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .patient-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
                .stats { display: flex; justify-content: space-between; margin-bottom: 20px; }
                .stat-item { text-align: center; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .section-title { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px; margin: 20px 0 10px 0; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Cabinet Médical LEZODIAQUE</h1>
                <h2>Dossier médical : ' . $patientCode . '</h2>
            </div>
            
            <div class="patient-info">
                <h3>Informations personnelles</h3>
                <p><strong>Nom :</strong> ' . htmlspecialchars($patient['nom']) . '</p>
                <p><strong>Prénom :</strong> ' . htmlspecialchars($patient['prenom']) . '</p>
                <p><strong>Date de naissance :</strong> ' . date('d/m/Y', strtotime($patient['date_naissance'])) . '</p>
                <p><strong>Téléphone :</strong> ' . htmlspecialchars($patient['telephone']) . '</p>
                <p><strong>Email :</strong> ' . htmlspecialchars($patient['email']) . '</p>
                <p><strong>Sexe :</strong> ' . ucfirst($patient['sexe']) . '</p>
                <p><strong>Groupe sanguin :</strong> ' . htmlspecialchars($patient['groupe_sanguin'] ?? 'Non renseigné') . '</p>
                <p><strong>Statut :</strong> ' . ucfirst($patient['statut_interne_externe']) . '</p>
            </div>
            
            <div class="stats">
                <div class="stat-item">
                    <h4>Nombre de consultations :</h4>
                    <p>' . $stats['consultations'] . '</p>
                </div>
                <div class="stat-item">
                    <h4>Nombre de rendez-vous :</h4>
                    <p>' . $stats['rendez_vous'] . '</p>
                </div>
                <div class="stat-item">
                    <h4>Nombre d\'ordonnances :</h4>
                    <p>' . $stats['ordonnances'] . '</p>
                </div>
                <div class="stat-item">
                    <h4>Total des frais payés :</h4>
                    <p>' . number_format($stats['frais_payes'], 0, ',', ' ') . ' ' . CURRENCY . '</p>
                </div>
            </div>
            
            <h3 class="section-title">Listes des consultations</h3>
            <table>
                <thead>
                    <tr>
                        <th>Motif</th>
                        <th>Date</th>
                        <th>Prix</th>
                        <th>Ordonnance</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($consultations as $consultation) {
            $html .= '<tr>
                <td>' . htmlspecialchars($consultation['motif']) . '</td>
                <td>' . date('d/m/Y H:i', strtotime($consultation['date_consultation'])) . '</td>
                <td>' . number_format($consultation['prix'], 0, ',', ' ') . ' ' . CURRENCY . '</td>
                <td>' . htmlspecialchars($consultation['ordonnance'] ?? 'Aucune') . '</td>
            </tr>';
        }
        
        $html .= '</tbody>
            </table>
            
            <div style="margin-top: 50px; text-align: center; color: #666;">
                <p>Document généré le ' . date('d/m/Y à H:i') . '</p>
                <p>Cabinet Médical LEZODIAQUE - Système de gestion médicale</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    private function generateSimplePDF($html, $filename) {
        if (class_exists('Dompdf\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            echo $dompdf->output();
            exit;
        }

        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . str_replace('.pdf', '.html', $filename) . '"');
        echo $html;
        exit;
    }
}

