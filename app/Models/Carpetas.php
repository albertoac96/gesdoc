<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carpetas extends Model
{
    use HasFactory;

    protected $table = 'carpetas';
    protected $primaryKey = 'idCarpeta';

    protected $fillable = [
        'cCarpeta',
        'idPadre',
        'iOrden',
        'cDescCarpeta',
        'idUsuario',
        'cEstatus',
        'idUsuarioBaja',
        'dBaja'
       
 
    ];


}
