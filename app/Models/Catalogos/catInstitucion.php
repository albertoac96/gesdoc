<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catInstitucion extends Model
{
    use HasFactory;
    protected $table = 'cat_instituciones';
    protected $primaryKey = 'idInstitucion';

    protected $fillable = [
        'idInstitucion',
        'cNombre',
        'idLugar',
        'cAbv'
    ];
}
