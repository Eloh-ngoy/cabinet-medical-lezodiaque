<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients - <?= APP_NAME ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #333; }
        .sidebar { position: fixed; left: 0; top: 0; width: 250px; height: 100vh; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar-menu a { display: flex; gap: .75rem; align-items: center; padding: 1rem 1.5rem; color: #fff; text-decoration: none; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: rgba(255,255,255,.12); }
        .main-content { margin-left: 250px; padding: 2rem; }
        .header, .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.08); }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; margin-bottom: 1.5rem; }
        .btn { display: inline-flex; align-items: center; gap: .5rem; padding: .7rem 1rem; border-radius: 6px; text-decoration: none; border: 0; cursor: pointer; }
        .btn-primary { background: #667eea; color: #fff; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .toolbar { display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem; }
        input, select, textarea { width: 100%; padding: .75rem; border: 1px solid #d1d5db; border-radius: 6px; }
        .card { padding: 1.5rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: .85rem; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background: #f9fafb; }
        .badge { display: inline-block; padding: .25rem .6rem; border-radius: 999px; font-size: .8rem; }
        .badge-externe { background: #e3f2fd; color: #1976d2; }
        .badge-interne { background: #fff3e0; color: #f57c00; }
        .alert { padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        .actions { display: flex; gap: .5rem; }
        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .main-content { margin-left: 0; } }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-user-md"></i> Health care</h2>
        </div>
        <nav class="sidebar-menu">
            <a href="<?= url('dashboard') ?>"><i class="fas fa-th-large"></i> Tableau de bord</a>
            <a class="active" href="<?= url('patients') ?>"><i class="fas fa-users"></i> Patients</a>
            <a href="<?= url('appointments') ?>"><i class="fas fa-calendar-alt"></i> Rendez-vous</a>
            <a href="<?= url('hospitalizations') ?>"><i class="fas fa-bed"></i> Hospitalisations</a>
            <a href="<?= url('medical-files') ?>"><i class="fas fa-folder-open"></i> Dossiers médicaux</a>
            <a href="<?= url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Patients</h1>
            <a class="btn btn-primary" href="<?= url('patients/create') ?>"><i class="fas fa-plus"></i> Nouveau patient</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Action effectuée avec succès.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">Une erreur est survenue.</div>
        <?php endif; ?>

        <div class="card">
            <form method="GET" class="toolbar">
                <input type="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par nom, prénom, email ou téléphone">
                <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i> Rechercher</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($patients)): ?>
                        <tr><td colspan="6">Aucun patient trouvé.</td></tr>
                    <?php else: ?>
                        <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?= htmlspecialchars('AA' . date('Y') . str_pad($patient['id'], 4, '0', STR_PAD_LEFT)) ?></td>
                            <td><?= htmlspecialchars($patient['nom'] . ' ' . $patient['prenom']) ?></td>
                            <td><?= htmlspecialchars($patient['telephone']) ?></td>
                            <td><?= htmlspecialchars($patient['email']) ?></td>
                            <td><span class="badge badge-<?= htmlspecialchars($patient['statut_interne_externe']) ?>"><?= ucfirst($patient['statut_interne_externe']) ?></span></td>
                            <td class="actions">
                                <a class="btn btn-primary" href="<?= url('medical-files/view?id=' . $patient['id']) ?>"><i class="fas fa-folder-open"></i> Dossier</a>
                                <a class="btn btn-secondary" href="<?= url('patients/edit?id=' . $patient['id']) ?>"><i class="fas fa-edit"></i> Modifier</a>
                                <form method="POST" action="<?= url('patients/delete') ?>" onsubmit="return confirm('Supprimer ce patient ?');">
                                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                    <input type="hidden" name="id" value="<?= $patient['id'] ?>">
                                    <button class="btn btn-danger" type="submit"><i class="fas fa-trash"></i></button>
                                </form>
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
