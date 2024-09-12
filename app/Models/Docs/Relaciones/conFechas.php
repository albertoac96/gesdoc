<?php

namespace App\Models\Docs\Relaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conFechas extends Model
{
    use HasFactory;
    protected $table = 'rel_docfecha';
    protected $primaryKey = 'idDocFecha';
    protected $fillable = [
       'idDocFecha',
       'idDoc',
       'dInicio',
       'dFin',
       'idUsrAlta'
    ];
}
