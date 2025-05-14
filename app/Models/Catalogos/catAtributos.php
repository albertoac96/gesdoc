<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catAtributos extends Model
{
    use HasFactory;
    protected $table = 'cat_atributos';
    protected $primaryKey = 'idAtributo';

    protected $fillable = [
        'idAtributo',
        'cAtributo',
        'cTipo',
        'idUsrAlta'
    ];
}
