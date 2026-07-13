<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - <?= APP_NAME ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            transition: all 0.3s ease;
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
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
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
        
        .header h1 {
            color: #333;
            font-size: 1.8rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-info span {
            color: #666;
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: #c0392b;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-card.patients .icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .stat-card.appointments .icon {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
        }
        
        .stat-card.consultations .icon {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
        }
        
        .stat-card.revenue .icon {
            background: linear-gradient(135deg, #43e97b, #38f9d7);
            color: white;
        }
        
        .stat-card.beds .icon {
            background: linear-gradient(135deg, #fa709a, #fee140);
            color: white;
        }
        
        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .stat-card p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .chart-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .chart-card h3 {
            margin-bottom: 1rem;
            color: #333;
        }
        
        .tables-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .table-card h3 {
            padding: 1rem 1.5rem;
            background: #f8fafc;
            margin: 0;
            color: #333;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .table-card table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table-card th,
        .table-card td {
            padding: 0.75rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .table-card th {
            background: #f8fafc;
            font-weight: 600;
            color: #333;
        }
        
        .table-card td {
            color: #666;
        }
        
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-badge.externe {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .status-badge.interne {
            background: #fff3e0;
            color: #f57c00;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .charts-grid,
            .tables-grid {
                grid-template-columns: 1fr;
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
            <a href="<?= url('dashboard') ?>" class="active">
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
            <h1>Tableau de bord</h1>
            <div class="user-info">
                <span>Bienvenue, <?= $_SESSION['full_name'] ?></span>
                <a href="<?= url('logout') ?>" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </a>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card patients">
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3><?= $stats['total_patients'] ?></h3>
                <p>Patients inscrits</p>
            </div>
            
            <div class="stat-card appointments">
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3><?= $stats['monthly_appointments'] ?></h3>
                <p>Rendez-vous ce mois</p>
            </div>
            
            <div class="stat-card consultations">
                <div class="icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <h3><?= $stats['monthly_consultations'] ?></h3>
                <p>Consultations ce mois</p>
            </div>
            
            <div class="stat-card revenue">
                <div class="icon">
                    <i class="fas fa-coins"></i>
                </div>
                <h3><?= number_format($stats['monthly_revenue'], 0, ',', ' ') ?> <?= CURRENCY ?></h3>
                <p>Revenus ce mois</p>
            </div>
            
            <div class="stat-card beds">
                <div class="icon">
                    <i class="fas fa-bed"></i>
                </div>
                <h3><?= $stats['available_beds'] ?>/<?= TOTAL_BEDS ?></h3>
                <p>Lits disponibles</p>
            </div>
        </div>
        
        <div class="charts-grid">
            <div class="chart-card">
                <h3><i class="fas fa-chart-line"></i> Évolution des consultations</h3>
                <canvas id="consultationsChart"></canvas>
            </div>
            
            <div class="chart-card">
                <h3><i class="fas fa-chart-pie"></i> Occupation des lits</h3>
                <canvas id="bedsChart"></canvas>
            </div>
        </div>
        
        <div class="tables-grid">
            <div class="table-card">
                <h3><i class="fas fa-user-plus"></i> Patients récents</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Nom</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentPatients as $patient): ?>
                        <tr>
                            <td><?= htmlspecialchars('AA' . date('Y') . str_pad($patient['id'], 4, '0', STR_PAD_LEFT)) ?></td>
                            <td><?= htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']) ?></td>
                            <td>
                                <span class="status-badge <?= $patient['status'] ?>">
                                    <?= ucfirst($patient['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="table-card">
                <h3><i class="fas fa-clock"></i> Prochains rendez-vous</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Motif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($upcomingAppointments as $appointment): ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']) ?></td>
                            <td><?= date('d/m H:i', strtotime($appointment['appointment_date'])) ?></td>
                            <td><?= htmlspecialchars(substr($appointment['reason'], 0, 30)) ?>...</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        // Graphique des consultations mensuelles
        const consultationsCtx = document.getElementById('consultationsChart').getContext('2d');
        const consultationsData = <?= json_encode($monthlyConsultations) ?>;
        
        new Chart(consultationsCtx, {
            type: 'line',
            data: {
                labels: consultationsData.map(item => {
                    const date = new Date(item.month + '-01');
                    return date.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
                }),
                datasets: [{
                    label: 'Consultations',
                    data: consultationsData.map(item => item.count),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Graphique d'occupation des lits
        const bedsCtx = document.getElementById('bedsChart').getContext('2d');
        const bedsData = <?= json_encode($bedOccupancy) ?>;
        
        new Chart(bedsCtx, {
            type: 'doughnut',
            data: {
                labels: bedsData.map(item => item.bed_type),
                datasets: [{
                    data: bedsData.map(item => item.occupied),
                    backgroundColor: [
                        '#667eea',
                        '#f093fb',
                        '#4facfe'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>

