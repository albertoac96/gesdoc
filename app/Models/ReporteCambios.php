<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteCambios extends Model
{
    use HasFactory;
    protected $table = 'rep_cambios';
    protected $primaryKey = 'idCambio';

    protected $fillable = [
        'idCambio',
        'cTipo',
        'idTipo',
        'cDescripcion',
        'idUsrAlta'
    ];
}
