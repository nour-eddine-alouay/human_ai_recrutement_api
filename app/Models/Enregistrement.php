<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enregistrement extends Model
{

    protected $fillable = [
        'offre_id',
        'user_id'
    ];

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }
}
