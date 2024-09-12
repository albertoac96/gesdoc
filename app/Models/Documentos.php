<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentos extends Model
{
    use HasFactory;
    protected $table = 'documentos';
    protected $primaryKey = 'idDocumento';

    protected $fillable = [
        'idCarpeta',
        'idTipoDoc',
        'cResumenDoc',
        'cTituloDoc',
        'cURL',
        'idUsrAlta',
        'cEstatus',
        'idUsuarioBaja',
        'dBaja',
        'cAutores',
       
 
    ];
}
