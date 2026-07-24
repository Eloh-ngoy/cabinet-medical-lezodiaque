<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends BaseModel
{
    protected $fillable = [
        'numero_unique',
        'nom',
        'prenom',
        'telephone',
        'email',
        'date_naissance',
        'sexe',
        'groupe_sanguin',
        'statut_interne_externe',
        'traitement_passe',
        'adresse',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'photo',
        'allergies',
        'antecedents',
        'maladies_chroniques',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'allergies' => 'array',
        'antecedents' => 'array',
        'maladies_chroniques' => 'array',
    ];

    /**
     * Génération du numéro unique patient (P-YYYY-XXXX)
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->numero_unique)) {
                $model->numero_unique = 'P-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(2)));
            }
        });
    }

    /**
     * Relation avec les consultations
     */
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    /**
     * Relation avec les rendez-vous
     */
    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }

    /**
     * Relation avec les hospitalisations
     */
    public function hospitalizations(): HasMany
    {
        return $this->hasMany(Hospitalization::class);
    }

    public function laboratoryAnalyses(): HasMany
    {
        return $this->hasMany(LaboratoryAnalysis::class);
    }

    public function getContactUrgenceAttribute(): string
    {
        if ($this->contact_urgence_nom && $this->contact_urgence_telephone) {
            return sprintf('%s (%s)', $this->contact_urgence_nom, $this->contact_urgence_telephone);
        }

        if ($this->contact_urgence_nom) {
            return $this->contact_urgence_nom;
        }

        if ($this->contact_urgence_telephone) {
            return $this->contact_urgence_telephone;
        }

        return 'Non renseigné';
    }
}