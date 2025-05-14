<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catGrupos extends Model
{
    use HasFactory;
     protected $table = 'cat_grupos';
    protected $primaryKey = 'idGrupo';

    protected $fillable = [
        'idGrupo',
        'cNombre',
    ];
}
