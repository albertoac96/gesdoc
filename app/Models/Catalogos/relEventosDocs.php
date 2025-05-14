<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class relEventosDocs extends Model
{
    use HasFactory;
    protected $table = 'rel_eventos_docs';
    protected $primaryKey = 'idRelEvDoc';
    const UPDATED_AT = null;
    #const CREATED_AT = null;
    protected $fillable = [
        'nPags',
        'idEvento',
        'idDoc'
    ];
}
