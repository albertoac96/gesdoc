<?php

namespace App\Models\Docs\Relaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conDocs extends Model
{
    use HasFactory;
    protected $table = 'rel_docdoc';
    protected $primaryKey = 'idRelDocDoc';
    protected $fillable = [
       'idRelDocAutor',
       'idDocumento',
       'idDocRelacionado',
       'cEstatus'
    ];
}
