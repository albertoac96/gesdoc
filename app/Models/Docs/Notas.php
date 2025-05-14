<?php

namespace App\Models\Docs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notas extends Model
{
    use HasFactory;
    protected $table = 'notas';
    protected $primaryKey = 'idNota';

    protected $fillable = [
        'idDocumento',
        'cTitulo',
        'cNota',
        'idUsrAlta',
        'cEstatus'
    ];
}
