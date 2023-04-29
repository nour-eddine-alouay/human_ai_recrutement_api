<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'contrat',
        'domaine',
        'status',
        'description',
        'pays',
        'adresse',
        'profil',
        'competences',
        'max_salaire',
        'min_salaire',
        'hide_salaire',
        'mode_travail',
        'niveau_etude',
        'niveau_experience',
        'user_id'
    ];
}
