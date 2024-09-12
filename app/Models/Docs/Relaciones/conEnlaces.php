<?php

namespace App\Models\Docs\Relaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conEnlaces extends Model
{
    use HasFactory;
    protected $table = 'rel_enlacesdoc';
    protected $primaryKey = 'idRelEnlDoc';
    protected $fillable = [
       'idRelArDoc',
       'idDocumento',
       'cTitulo',
       'cURL',
       'idUsrAlta',
       'cEstatus'
    ];
}
