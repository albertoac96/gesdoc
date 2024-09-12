<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catTipoAutor extends Model
{
    use HasFactory;
    protected $table = 'cat_tipoautor';
    protected $primaryKey = 'idTipoAutor';

    protected $fillable = [
        'idTipoAutor',
        'cTipoAutor',
        'idUsrAlta'
    ];
}
