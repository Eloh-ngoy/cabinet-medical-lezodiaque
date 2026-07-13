<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau patient - <?= APP_NAME ?></title>
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
            <a href="<?= url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Nouveau patient</h1>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form class="card" method="POST" action="<?= url('patients/store') ?>">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            <div class="form-grid">
                <div>
                    <label for="nom">Nom</label>
                    <input id="nom" name="nom" required>
                </div>
                <div>
                    <label for="prenom">Prénom</label>
                    <input id="prenom" name="prenom" required>
                </div>
                <div>
                    <label for="telephone">Téléphone</label>
                    <input id="telephone" name="telephone" required>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" required>
                </div>
                <div>
                    <label for="date_naissance">Date de naissance</label>
                    <input id="date_naissance" name="date_naissance" type="date" required>
                </div>
                <div>
                    <label for="sexe">Sexe</label>
                    <select id="sexe" name="sexe" required>
                        <option value="homme">Homme</option>
                        <option value="femme">Femme</option>
                    </select>
                </div>
                <div>
                    <label for="groupe_sanguin">Groupe sanguin</label>
                    <input id="groupe_sanguin" name="groupe_sanguin">
                </div>
                <div>
                    <label for="statut_interne_externe">Statut</label>
                    <select id="statut_interne_externe" name="statut_interne_externe">
                        <option value="externe">Externe</option>
                        <option value="interne">Interne</option>
                    </select>
                </div>
                <div style="grid-column: 1 / -1;">
                    <label for="traitement_passe">Traitement passé</label>
                    <textarea id="traitement_passe" name="traitement_passe"></textarea>
                </div>
            </div>
            <div class="actions">
                <button class="btn btn-primary" type="submit">Enregistrer</button>
                <a class="btn btn-secondary" href="<?= url('patients') ?>">Annuler</a>
            </div>
        </form>
    </main>
</body>
</html>
