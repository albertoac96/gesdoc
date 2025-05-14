<?php

namespace App\Models\Docs\Relaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conTemas extends Model
{
    use HasFactory;
    protected $table = 'rel_doctema';
    protected $primaryKey = 'idDocTema';
    protected $fillable = [
       'idDocTema',
       'idDocumento',
       'idTema',
       'idUsrAlta',
       'cEstatus'
    ];
}
