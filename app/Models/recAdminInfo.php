<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recAdminInfo extends Model
{
    use HasFactory;

    // table name :
    protected $table = 'recruteur_admin_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email_contact',
        'poste',
        'linkedin',
        'isCompleted',
        'user_id'
    ];
}
