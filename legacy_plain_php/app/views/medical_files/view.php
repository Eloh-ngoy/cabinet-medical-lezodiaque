<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier médical - <?= APP_NAME ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            color: #333;
        }
        
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h2 {
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            border-right: 3px solid white;
        }
        
        .sidebar-menu a i {
            margin-right: 1rem;
            width: 20px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        
        .header {
            background: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .patient-code {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .export-btn {
            background: #28a745;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .export-btn:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .info-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .card-header {
            background: #f8fafc;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-header i {
            color: #667eea;
        }
        
        .card-header h3 {
            color: #333;
            font-size: 1.1rem;
            margin: 0;
        }
        
        .card-content {
            padding: 1.5rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 500;
            color: #666;
        }
        
        .info-value {
            color: #333;
            font-weight: 600;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        
        .stat-item {
            text-align: center;
            padding: 1rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .consultations-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .consultations-header {
            background: #f8fafc;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .consultations-header h3 {
            color: #333;
            font-size: 1.1rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .consultations-header i {
            color: #667eea;
        }
        
        .add-btn {
            background: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .add-btn:hover {
            background: #5a6fd8;
        }
        
        .consultations-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .consultations-table th,
        .consultations-table td {
            padding: 1rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .consultations-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #333;
        }
        
        .consultations-table td {
            color: #666;
        }
        
        .consultations-table tbody tr:hover {
            background: #f8fafc;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-user-md"></i> Health care</h2>
        </div>
        <nav class="sidebar-menu">
            <a href="<?= url('dashboard') ?>">
                <i class="fas fa-th-large"></i>
                Tableau de bord
            </a>
            <a href="<?= url('patients') ?>">
                <i class="fas fa-users"></i>
                Patients
            </a>
            <a href="<?= url('appointments') ?>">
                <i class="fas fa-calendar-alt"></i>
                Rendez-vous
            </a>
            <a href="<?= url('logout') ?>">
                <i class="fas fa-sign-out-alt"></i>
                Se déconnecter
            </a>
        </nav>
    </div>
    
    <div class="main-content">
        <div class="header">
            <div class="patient-code">
                Dossier médical : <?= htmlspecialchars('AA' . date('Y') . str_pad($patient['id'], 4, '0', STR_PAD_LEFT)) ?>
            </div>
            <a href="<?= url('medical-files/export-pdf?id=' . $patient['id']) ?>" class="export-btn">
                <i class="fas fa-file-pdf"></i>
                Exporter en PDF
            </a>
        </div>
        
        <div class="content-grid">
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-user"></i>
                    <h3>Informations personnelles</h3>
                </div>
                <div class="card-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nom :</span>
                            <span class="info-value"><?= htmlspecialchars($patient['nom']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Prénom :</span>
                            <span class="info-value"><?= htmlspecialchars($patient['prenom']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Date de naissance :</span>
                            <span class="info-value"><?= date('d/m/Y', strtotime($patient['date_naissance'])) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Téléphone :</span>
                            <span class="info-value"><?= htmlspecialchars($patient['telephone']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email :</span>
                            <span class="info-value"><?= htmlspecialchars($patient['email']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Sexe :</span>
                            <span class="info-value"><?= ucfirst($patient['sexe']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Groupe sanguin :</span>
                            <span class="info-value"><?= htmlspecialchars($patient['groupe_sanguin'] ?? 'Non renseigné') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Statut :</span>
                            <span class="info-value"><?= ucfirst($patient['statut_interne_externe']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-chart-bar"></i>
                    <h3>Statistiques</h3>
                </div>
                <div class="card-content">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value"><?= $stats['consultations'] ?></div>
                            <div class="stat-label">Nombre de consultations :</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?= $stats['rendez_vous'] ?></div>
                            <div class="stat-label">Nombre de rendez-vous :</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?= $stats['ordonnances'] ?></div>
                            <div class="stat-label">Nombre d'ordonnances :</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?= number_format($stats['frais_payes'], 0, ',', ' ') ?><?= CURRENCY ?></div>
                            <div class="stat-label">Total des frais payés:</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="consultations-section">
            <div class="consultations-header">
                <h3>
                    <i class="fas fa-list"></i>
                    Listes des consultations
                </h3>
                <a href="<?= url('consultations/create?patient_id=' . $patient['id']) ?>" class="add-btn">
                    Ajouter
                </a>
            </div>
            
            <?php if (empty($consultations)): ?>
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <p>Aucune consultation enregistrée pour ce patient</p>
                </div>
            <?php else: ?>
                <table class="consultations-table">
                    <thead>
                        <tr>
                            <th>Motif</th>
                            <th>Date</th>
                            <th>Prix</th>
                            <th>Ordonnance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consultations as $consultation): ?>
                        <tr>
                            <td><?= htmlspecialchars($consultation['motif']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($consultation['date_consultation'])) ?></td>
                            <td><?= number_format($consultation['prix'], 0, ',', ' ') ?> <?= CURRENCY ?></td>
                            <td><?= htmlspecialchars($consultation['ordonnance'] ?? 'Aucune') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

