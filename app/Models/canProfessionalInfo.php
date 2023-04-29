<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class canProfessionalInfo extends Model
{
    use HasFactory;

    // table name :
    protected $table = 'candidat_professional_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'domaine',
        'profession',
        'competences',
        'apercu',
        'niveau_etude',
        'niveau_experience',
        'isCompleted',
        'user_id'
    ];
}
