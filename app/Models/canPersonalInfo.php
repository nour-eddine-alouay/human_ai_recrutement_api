<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class canPersonalInfo extends Model
{
    use HasFactory;

    // table name :
    protected $table = 'candidat_personal_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'civilite',
        'etat_civilite',
        'date_naissance',
        'telephone',
        'pays',
        'ville',
        'linkedin',
        'skype',
        'isCompleted',
        'user_id'
    ];
}
