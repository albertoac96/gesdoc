<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catLugares extends Model
{
    use HasFactory;
      protected $table = 'cat_lugares';
    protected $primaryKey = 'idLugar';

    protected $fillable = [
        'cNombre',
        'cLat',
        'cLong',
        'idPadre'
    ];
}

