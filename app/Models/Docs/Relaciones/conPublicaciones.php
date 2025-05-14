<?php

namespace App\Models\Docs\Relaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conPublicaciones extends Model
{
    use HasFactory;
    protected $table = 'rel_pubdoc';
    protected $primaryKey = 'idRelPubDoc';
    protected $fillable = [
       'idRelPubDoc',
       'idDocumento',
       'idPublicacion',
       'idUsrAlta'
    ];
}
