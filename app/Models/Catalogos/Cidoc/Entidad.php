<?php

namespace App\Models\Catalogos\Cidoc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    use HasFactory;
    protected $table = 'cat_cidoc_ents';
    protected $primaryKey = 'idEntidad';

    protected $fillable = [
        'CveEntidad',
        'cNombre',
    ];
}
