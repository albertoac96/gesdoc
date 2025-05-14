<?php

namespace App\Models\Docs\Relaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conAtributos extends Model
{
    use HasFactory;
    protected $table = 'rel_docatr';
    protected $primaryKey = 'idDocAtr';
    protected $fillable = [
       'idDocAtr',
       'idDocumento',
       'idAtributo',
       'cValor',
       'cEstatus',
       'idUsrAlta'
    ];
}
