<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contenidoPDF extends Model
{
    use HasFactory;
    protected $table = 'cont_pdf';
    protected $primaryKey = 'idContPdf';

    protected $fillable = [
        'idContPdf',
        'idDocumento',
        'cConetnido'
       
    ];
}
