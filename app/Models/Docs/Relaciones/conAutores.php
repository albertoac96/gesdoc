<?php

namespace App\Models\Docs\Relaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conAutores extends Model
{
    use HasFactory;
    protected $table = 'rel_docautor';
    protected $primaryKey = 'idRelDocAutor';
    protected $fillable = [
       'idRelDocAutor',
       'idDoc',
       'idAutor',
       'idTipoAutor',
       'iOrden',
       'idUsrAlta'
    ];
}
