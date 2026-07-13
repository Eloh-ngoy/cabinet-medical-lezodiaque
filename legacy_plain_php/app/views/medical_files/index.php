<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossiers médicaux - <?= APP_NAME ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #333; }
        .sidebar { position: fixed; left: 0; top: 0; width: 250px; height: 100vh; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar-menu a { display: flex; gap: .75rem; align-items: center; padding: 1rem 1.5rem; color: #fff; text-decoration: none; }
        .sidebar-menu a.active { background: rgba(255,255,255,.12); }
        .main-content { margin-left: 250px; padding: 2rem; }
        .header, .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.08); }
        .header { padding: 1rem 1.5rem; margin-bottom: 1.5rem; }
        .card { padding: 1.5rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: .85rem; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background: #f9fafb; }
        .btn { display: inline-flex; align-items: center; gap: .5rem; padding: .7rem 1rem; border-radius: 6px; text-decoration: none; border: 0; cursor: pointer; }
        .btn-primary { background: #667eea; color: #fff; }
        .alert { padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .main-content { margin-left: 0; } }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header"><h2><i class="fas fa-user-md"></i> Health care</h2></div>
        <nav class="sidebar-menu">
            <a href="<?= url('dashboard') ?>"><i class="fas fa-th-large"></i> Tableau de bord</a>
            <a href="<?= url('patients') ?>"><i class="fas fa-users"></i> Patients</a>
            <a href="<?= url('appointments') ?>"><i class="fas fa-calendar-alt"></i> Rendez-vous</a>
            <a href="<?= url('hospitalizations') ?>"><i class="fas fa-bed"></i> Hospitalisations</a>
            <a class="active" href="<?= url('medical-files') ?>"><i class="fas fa-folder-open"></i> Dossiers médicaux</a>
            <a href="<?= url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="header"><h1>Dossiers médicaux</h1></div>
        <?php if (isset($_GET['success'])): ?><div class="alert alert-success">Consultation enregistrée.</div><?php endif; ?>
        <?php if (isset($_GET['error'])): ?><div class="alert alert-error">Une erreur est survenue.</div><?php endif; ?>

        <div class="card">
            <table>
                <thead>
                    <tr><th>Code</th><th>Patient</th><th>Téléphone</th><th>Statut</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($patients)): ?>
                        <tr><td colspan="5">Aucun patient trouvé.</td></tr>
                    <?php else: ?>
                        <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?= htmlspecialchars('AA' . date('Y') . str_pad($patient['id'], 4, '0', STR_PAD_LEFT)) ?></td>
                            <td><?= htmlspecialchars($patient['nom'] . ' ' . $patient['prenom']) ?></td>
                            <td><?= htmlspecialchars($patient['telephone']) ?></td>
                            <td><?= ucfirst($patient['statut_interne_externe']) ?></td>
                            <td>
                                <a class="btn btn-primary" href="<?= url('medical-files/view?id=' . $patient['id']) ?>">Ouvrir le dossier</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
