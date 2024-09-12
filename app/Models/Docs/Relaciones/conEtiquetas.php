<?php

namespace App\Models\Docs\Relaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conEtiquetas extends Model
{
    use HasFactory;
    protected $table = 'rel_etiquetadoc';
    protected $primaryKey = 'idEtiqueta';
    protected $fillable = [
       'idEtiqueta',
       'idDocumento',
       'idUsrAlta'
    ];
}
