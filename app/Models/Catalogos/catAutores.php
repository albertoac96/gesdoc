<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catAutores extends Model
{
    use HasFactory;
    protected $table = 'cat_autores';
    protected $primaryKey = 'idAutor';

    protected $fillable = [
        'idAutor',
        'cNombre',
        'cFirstNombre',
        'cApellido',
        'idUsrAlta',
        'cORCID'
       
    ];
}
