<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catTemas extends Model
{
    use HasFactory;
    protected $table = 'temas';
    protected $primaryKey = 'idTema';

    protected $fillable = [
        'idTema',
        'cTema',
        'cDescTema',
        'cEstatus',
        'cColor',
        'idUsrAlta'
    ];
}
