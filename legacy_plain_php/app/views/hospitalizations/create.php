<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle hospitalisation - <?= APP_NAME ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #333; }
        .sidebar { position: fixed; left: 0; top: 0; width: 250px; height: 100vh; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar-menu a { display: flex; gap: .75rem; align-items: center; padding: 1rem 1.5rem; color: #fff; text-decoration: none; }
        .main-content { margin-left: 250px; padding: 2rem; }
        .header, .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.08); }
        .header { padding: 1rem 1.5rem; margin-bottom: 1.5rem; }
        .card { padding: 1.5rem; max-width: 800px; }
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        label { display: block; margin-bottom: .4rem; font-weight: 600; }
        input, select, textarea { width: 100%; padding: .75rem; border: 1px solid #d1d5db; border-radius: 6px; }
        textarea { min-height: 110px; }
        .actions { margin-top: 1.5rem; display: flex; gap: .75rem; }
        .btn { padding: .75rem 1rem; border-radius: 6px; text-decoration: none; border: 0; cursor: pointer; }
        .btn-primary { background: #667eea; color: #fff; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .alert { padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1rem; background: #fee2e2; color: #991b1b; }
        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .main-content { margin-left: 0; } .form-grid { grid-template-columns: 1fr; } }
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
            <a href="<?= url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="header"><h1>Nouvelle hospitalisation</h1></div>
        <?php if (isset($error)): ?><div class="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form class="card" method="POST" action="<?= url('hospitalizations/store') ?>">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            <div class="form-grid">
                <div>
                    <label for="patient_id">Patient</label>
                    <select id="patient_id" name="patient_id" required>
                        <?php foreach ($patients as $patient): ?>
                            <option value="<?= $patient['id'] ?>"><?= htmlspecialchars($patient['nom'] . ' ' . $patient['prenom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="bed_id">Lit disponible</label>
                    <select id="bed_id" name="bed_id" required>
                        <?php if (empty($beds)): ?>
                            <option value="">Aucun lit disponible</option>
                        <?php else: ?>
                            <?php foreach ($beds as $bed): ?>
                                <option value="<?= $bed['id'] ?>">Lit <?= htmlspecialchars($bed['bed_number']) ?> - Chambre <?= htmlspecialchars($bed['room_number']) ?> (<?= htmlspecialchars($bed['bed_type']) ?>)</option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div>
                    <label for="admission_date">Date d’admission</label>
                    <input id="admission_date" name="admission_date" type="datetime-local" value="<?= date('Y-m-d\TH:i') ?>" required>
                </div>
                <div>
                    <label for="expected_duration">Durée prévue (jours)</label>
                    <input id="expected_duration" name="expected_duration" type="number" min="1" value="3">
                </div>
                <div style="grid-column: 1 / -1;">
                    <label for="admission_reason">Motif d’admission</label>
                    <textarea id="admission_reason" name="admission_reason" required></textarea>
                </div>
            </div>
            <div class="actions">
                <button class="btn btn-primary" type="submit">Hospitaliser</button>
                <a class="btn btn-secondary" href="<?= url('hospitalizations') ?>">Annuler</a>
            </div>
        </form>
    </main>
</body>
</html>
