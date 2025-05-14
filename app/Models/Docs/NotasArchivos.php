<?php

namespace App\Models\Docs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotasArchivos extends Model
{
    use HasFactory;
    protected $table = 'notas_archivos';
    protected $primaryKey = 'idNotaArchivo';
    protected $fillable = [
       'idNotaArchivo',
       'cNota',
       'cContenido',
       'idArchivo',
       'iPagina',
       'idUsrAlta',
       'cEstatus'
    ];
}
