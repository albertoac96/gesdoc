<?php

namespace App\Models\Docs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class relTipoDocAtr extends Model
{
    use HasFactory;
    protected $table = 'rel_tipodocatr';
    protected $primaryKey = 'idTipoDocAtr';
    protected $fillable = [
       'idTipoDocAtr',
       'idTipoDoc',
       'idAtributo',
       'idUsrAlta'
    ];
}
