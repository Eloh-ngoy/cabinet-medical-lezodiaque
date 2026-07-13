# MediNexus — Système d'Information Hospitalier Intelligent

Application web de gestion hospitalière développée avec Laravel, destinée aux structures de santé pour la gestion centralisée des patients, du personnel médical, des consultations, hospitalisations, prescriptions, laboratoire et pharmacie.

## Technologies

- **Backend** : Laravel 11, PHP 8.3+
- **Base de données** : SQLite (configurable pour PostgreSQL/MySQL)
- **Frontend** : Blade + Tailwind CSS (CDN)
- **Packages** : spatie/laravel-permission, spatie/laravel-activitylog, barryvdh/laravel-dompdf

## Installation

```bash
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

## Identifiants par défaut

- **Utilisateur** : `admin`
- **Mot de passe** : `admin123`

## Modules

| Module | Description |
|--------|-------------|
| **Patients** | Gestion complète des patients (CRUD, recherche, dossier médical) |
| **Consultations** | Consultations liées au médecin et au patient |
| **Rendez-vous** | Gestion des rendez-vous (planifié, confirmé, annulé, terminé) |
| **Hospitalisations** | Admission, suivi des lits/chambres, sortie |
| **Laboratoire** | Demandes d'analyses, saisie des résultats, validation |
| **Pharmacie** | Gestion des stocks, délivrance, réapprovisionnement |
| **Documents PDF** | Dossier médical, ordonnance, rapports, audit patient |
| **Utilisateurs** | Gestion du personnel avec rôles et permissions |
| **API REST** | API REST complète sous `/api/v1/` |

## Rôles et permissions

| Rôle | Permissions principales |
|------|------------------------|
| Directeur Général Médecin | Super administrateur — accès total |
| Médecin | Consultations, prescriptions, analyses, hospitalisations |
| Infirmier | Suivi patients, observations infirmières |
| Pharmacien | Prescriptions, délivrance, stock pharmaceutique |
| Laborantin | Demandes et résultats d'analyses |
| Réceptionniste | Enregistrement patients, rendez-vous |

## Sécurité

- Authentification par nom d'utilisateur
- Hashage des mots de passe (bcrypt)
- Protection CSRF
- Permissions strictes par rôle (Spatie)
- Journalisation complète des actions (ActivityLog)
- Suppression logique (Soft Deletes)
- Changement de mot de passe forcé à la première connexion
- Form Requests pour la validation

## Architecture

- **Controllers** : `app/Http/Controllers/`
- **API Controllers** : `app/Http/Controllers/Api/`
- **Models** : `app/Models/`
- **Services** : `app/Services/`
- **Policies** : `app/Policies/`
- **Form Requests** : `app/Http/Requests/`
- **API Resources** : `app/Http/Resources/`
- **Views** : `resources/views/`
- **Layout** : `resources/views/layouts/app.blade.php`

## Tests

```bash
php artisan test
```

## Documentation

Le cahier des charges complet est disponible dans `cahier_des_charges.txt`.
