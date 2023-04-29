<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recEntrepriseInfo extends Model
{
    use HasFactory;

    // table name :
    protected $table = 'recruteur_entreprise_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'domaine',
        'type',
        'apercu',
        'slogan',
        'pays',
        'adresse',
        'site',
        'isCompleted',
        'user_id'
    ];
}
