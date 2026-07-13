<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospitalisations - <?= APP_NAME ?></title>
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
        .header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; margin-bottom: 1.5rem; }
        .card { padding: 1.5rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: .85rem; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background: #f9fafb; }
        .btn { display: inline-flex; align-items: center; gap: .5rem; padding: .7rem 1rem; border-radius: 6px; text-decoration: none; border: 0; cursor: pointer; }
        .btn-primary { background: #667eea; color: #fff; }
        .btn-danger { background: #dc3545; color: #fff; }
        .badge { display: inline-block; padding: .25rem .6rem; border-radius: 999px; font-size: .8rem; }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-termine { background: #e5e7eb; color: #374151; }
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
            <a class="active" href="<?= url('hospitalizations') ?>"><i class="fas fa-bed"></i> Hospitalisations</a>
            <a href="<?= url('medical-files') ?>"><i class="fas fa-folder-open"></i> Dossiers médicaux</a>
            <a href="<?= url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Hospitalisations</h1>
            <a class="btn btn-primary" href="<?= url('hospitalizations/create') ?>"><i class="fas fa-plus"></i> Nouvelle hospitalisation</a>
        </div>

        <?php if (isset($_GET['success'])): ?><div class="alert alert-success">Action effectuée avec succès.</div><?php endif; ?>
        <?php if (isset($_GET['error'])): ?><div class="alert alert-error">Une erreur est survenue.</div><?php endif; ?>

        <div class="card">
            <table>
                <thead>
                    <tr><th>Patient</th><th>Lit</th><th>Admission</th><th>Durée prévue</th><th>Statut</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($hospitalizations)): ?>
                        <tr><td colspan="6">Aucune hospitalisation trouvée.</td></tr>
                    <?php else: ?>
                        <?php foreach ($hospitalizations as $hospitalization): ?>
                        <tr>
                            <td><?= htmlspecialchars($hospitalization['prenom'] . ' ' . $hospitalization['nom']) ?></td>
                            <td>Lit <?= htmlspecialchars($hospitalization['bed_number']) ?> - Chambre <?= htmlspecialchars($hospitalization['room_number']) ?> (<?= htmlspecialchars($hospitalization['bed_type']) ?>)</td>
                            <td><?= date('d/m/Y H:i', strtotime($hospitalization['admission_date'])) ?></td>
                            <td><?= $hospitalization['expected_duration'] ? htmlspecialchars($hospitalization['expected_duration'] . ' jours') : '-' ?></td>
                            <td><span class="badge badge-<?= htmlspecialchars($hospitalization['status']) ?>"><?= ucfirst($hospitalization['status']) ?></span></td>
                            <td>
                                <?php if ($hospitalization['status'] === 'active'): ?>
                                    <form method="POST" action="<?= url('hospitalizations/discharge') ?>" onsubmit="return confirm('Clôturer cette hospitalisation ?');">
                                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                        <input type="hidden" name="id" value="<?= $hospitalization['id'] ?>">
                                        <input type="hidden" name="discharge_notes" value="">
                                        <button class="btn btn-danger" type="submit">Clôturer</button>
                                    </form>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
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
