<?php

class DashboardController {
    private $db;
    
    public function __construct() {
        $this->db = db();
    }
    
    public function index() {
        $stats = $this->getGeneralStats();
        $monthlyConsultations = $this->getMonthlyConsultations();
        $bedOccupancy = $this->getBedOccupancy();
        $recentPatients = $this->getRecentPatients();
        $upcomingAppointments = $this->getUpcomingAppointments();
        
        include __DIR__ . '/../views/dashboard/index.php';
    }
    
    private function getGeneralStats() {
        $stats = [];
        
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM patients");
        $stats['total_patients'] = (int) $stmt->fetch()['total'];
        
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM rendez_vouses WHERE MONTH(date_heure) = MONTH(NOW()) AND YEAR(date_heure) = YEAR(NOW())");
        $stats['monthly_appointments'] = (int) $stmt->fetch()['total'];
        
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM consultations WHERE MONTH(date_consultation) = MONTH(NOW()) AND YEAR(date_consultation) = YEAR(NOW())");
        $stats['monthly_consultations'] = (int) $stmt->fetch()['total'];
        
        $stmt = $this->db->query("SELECT COALESCE(SUM(prix), 0) as total FROM consultations WHERE MONTH(date_consultation) = MONTH(NOW()) AND YEAR(date_consultation) = YEAR(NOW())");
        $stats['monthly_revenue'] = (float) $stmt->fetch()['total'];
        
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM hospitalizations WHERE status = 'active'");
        $stats['hospitalized_patients'] = (int) $stmt->fetch()['total'];
        
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM beds WHERE is_available = 1");
        $stats['available_beds'] = (int) $stmt->fetch()['total'];
        
        return $stats;
    }
    
    private function getMonthlyConsultations() {
        $stmt = $this->db->query("
            SELECT 
                DATE_FORMAT(date_consultation, '%Y-%m') as month,
                COUNT(*) as count
            FROM consultations 
            WHERE date_consultation >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(date_consultation, '%Y-%m')
            ORDER BY month
        ");
        
        return $stmt->fetchAll();
    }
    
    private function getBedOccupancy() {
        $stmt = $this->db->query("
            SELECT 
                bed_type,
                COUNT(*) as total,
                SUM(CASE WHEN is_available = 0 THEN 1 ELSE 0 END) as occupied
            FROM beds 
            GROUP BY bed_type
        ");
        
        return $stmt->fetchAll();
    }
    
    private function getRecentPatients() {
        $stmt = $this->db->query("
            SELECT 
                id,
                nom,
                prenom,
                statut_interne_externe as status,
                created_at
            FROM patients 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        
        return $stmt->fetchAll();
    }
    
    private function getUpcomingAppointments() {
        $stmt = $this->db->query("
            SELECT 
                r.date_heure as appointment_date,
                r.motif as reason,
                p.prenom as first_name,
                p.nom as last_name,
                p.id
            FROM rendez_vouses r
            JOIN patients p ON r.patient_id = p.id
            WHERE r.date_heure >= NOW()
            AND r.statut IN ('planifie', 'confirme')
            ORDER BY r.date_heure
            LIMIT 5
        ");
        
        return $stmt->fetchAll();
    }
}

