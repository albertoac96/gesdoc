<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catTipoPub extends Model
{
    use HasFactory;
    protected $table = 'cat_tipopub';
    protected $primaryKey = 'idCatTipoPub';

    protected $fillable = [
        'idCatTipoPub',
        'cTipoPub',
        'idCat_TipoDoc'
    ];
}
