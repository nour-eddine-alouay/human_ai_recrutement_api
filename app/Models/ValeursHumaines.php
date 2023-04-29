<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValeursHumaines extends Model
{
    use HasFactory;

    // table name :
    protected $table = 'valeurs_humaines';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "agilite",
        "apprentissage_continu",
        "authenticite",
        "efficacite",
        "empathie",
        "engagement_envers_client",
        "excellence",
        "humilite",
        "innovation",
        "integrite",
        "orientationeclient",
        "orientation_resultats",
        "ouverture_esprit",
        "collaboration_travail_equipe",
        "communication",
        "confiance",
        "confiance_en_soi",
        "confidentialite",
        "creativite",
        "croissance",
        "croissance_personnelle",
        "culture_apprentissage",
        "diversite_inclusion",
        "durabilite",
        "flexibilite",
        "flexibilite_horaire",
        "gestion_temps",
        "loyaute",
        "passion",
        "qualite",
        "qualite_vie_travail",
        "reactivite",
        "reconnaissance",
        "resilience",
        "resolution_problemes",
        "responsabilite_environnementale",
        "responsabilite_personnelle",
        "responsabilite_sociale",
        "securite",
        "securite_financiere",
        "transparence",
        "volonte_prendre_risques",
        "respect",
        'user_id'
    ];
}
