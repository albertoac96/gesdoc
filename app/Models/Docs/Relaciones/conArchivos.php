<?php

namespace App\Models\Docs\Relaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conArchivos extends Model
{
    use HasFactory;
    protected $table = 'rel_archivosdoc';
    protected $primaryKey = 'idRelArDoc';
    protected $fillable = [
       'idRelArDoc',
       'idDocumento',
       'cNombre',
       'iMostrar',
       'cImagen',
       'idUsrAlta',
       'cEstatus',
       'idExt'
    ];
}
