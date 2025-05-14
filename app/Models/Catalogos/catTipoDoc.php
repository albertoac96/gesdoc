<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catTipoDoc extends Model
{
    use HasFactory;

    protected $table = 'cat_tipodoc';
    protected $primaryKey = 'idTipoDoc';
    #const UPDATED_AT = null;
    #const CREATED_AT = null;
    protected $fillable = [
        'idTipoDoc',
        'cTipoDoc',
        'cIcono',
        'cColor',
        'idUsrAlta',
        'cEstatus'
    ];

}
