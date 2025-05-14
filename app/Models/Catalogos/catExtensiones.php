<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catExtensiones extends Model
{
    use HasFactory;
    protected $table = 'cat_extensiones';
    protected $primaryKey = 'idExtension';

    protected $fillable = [
        'cExtension',
        'cIcono',
       
    ];
}
