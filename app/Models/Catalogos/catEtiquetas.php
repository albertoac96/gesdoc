<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catEtiquetas extends Model
{
    use HasFactory;
    protected $table = 'cat_etiquetas';
    protected $primaryKey = 'idEtiqueta';

    protected $fillable = [
        'idEtiqueta',
        'cEtiqueta',
        'idUsrAlta',
        'cEstatus'
       
    ];
}
