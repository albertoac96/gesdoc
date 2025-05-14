<?php

namespace App\Models\Catalogos\Cidoc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    use HasFactory;
    protected $table = 'cat_cidoc_props';
    protected $primaryKey = 'idPropiedad';

    protected $fillable = [
        'CvePropiedad',
        'cNombre',
    ];
}
