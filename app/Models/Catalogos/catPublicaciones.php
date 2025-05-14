<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catPublicaciones extends Model
{
    use HasFactory;
    protected $table = 'cat_publicaciones';
    protected $primaryKey = 'idPublicacion';

    protected $fillable = [
        'idPublicacion',
        'cPublicacion',
        'idCat_TipoPub',
        'idUsrAlta',
        'cEstatus'
       
    ];
}
