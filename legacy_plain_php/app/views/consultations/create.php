<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle consultation - <?= APP_NAME ?></title>
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
        .card { padding: 1.5rem; max-width: 900px; }
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        label { display: block; margin-bottom: .4rem; font-weight: 600; }
        input, select, textarea { width: 100%; padding: .75rem; border: 1px solid #d1d5db; border-radius: 6px; }
        textarea { min-height: 90px; }
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
            <a href="<?= url('medical-files') ?>"><i class="fas fa-folder-open"></i> Dossiers médicaux</a>
            <a href="<?= url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="header"><h1>Nouvelle consultation</h1></div>
        <?php if (isset($error)): ?><div class="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form class="card" method="POST" action="<?= url('consultations/store') ?>">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            <div class="form-grid">
                <div>
                    <label for="patient_id">Patient</label>
                    <select id="patient_id" name="patient_id" required>
                        <?php foreach ($patients as $patientOption): ?>
                            <option value="<?= $patientOption['id'] ?>" <?= $selectedPatientId === (int) $patientOption['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($patientOption['nom'] . ' ' . $patientOption['prenom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="date_consultation">Date de consultation</label>
                    <input id="date_consultation" name="date_consultation" type="datetime-local" value="<?= date('Y-m-d\TH:i') ?>" required>
                </div>
                <div style="grid-column: 1 / -1;">
                    <label for="motif">Motif</label>
                    <textarea id="motif" name="motif" required></textarea>
                </div>
                <div style="grid-column: 1 / -1;">
                    <label for="diagnostic">Diagnostic</label>
                    <textarea id="diagnostic" name="diagnostic"></textarea>
                </div>
                <div style="grid-column: 1 / -1;">
                    <label for="traitement">Traitement</label>
                    <textarea id="traitement" name="traitement"></textarea>
                </div>
                <div>
                    <label for="prix">Prix</label>
                    <input id="prix" name="prix" type="number" step="0.01" min="0" value="0" required>
                </div>
                <div style="grid-column: 1 / -1;">
                    <label for="ordonnance">Ordonnance</label>
                    <textarea id="ordonnance" name="ordonnance"></textarea>
                </div>
            </div>
            <div class="actions">
                <button class="btn btn-primary" type="submit">Enregistrer</button>
                <a class="btn btn-secondary" href="<?= $patient ? url('medical-files/view?id=' . $patient['id']) : url('medical-files') ?>">Annuler</a>
            </div>
        </form>
    </main>
</body>
</html>
