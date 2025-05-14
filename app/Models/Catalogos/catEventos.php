<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catEventos extends Model
{
    use HasFactory;
    protected $table = 'cat_eventos';
     protected $primaryKey = 'idEvento';

    protected $fillable = [
        'idTipoEvento',
        'cRelacion',
        'idActor',
        'idActorRel',
        'cDescripcion',
        'dFecha',
        'idUsrAlta',
        'idTipoActor',
        'idTipoActorRel'
    ];

   
}
