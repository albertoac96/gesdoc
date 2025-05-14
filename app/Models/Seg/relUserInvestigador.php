<?php

namespace App\Models\Seg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class relUserInvestigador extends Model
{
    use HasFactory;
    protected $table = 'rel_invuser';
    protected $primaryKey = 'idRelInvUser';
    public $timestamps = false;
    protected $fillable = [
       'idRelInvUser',
       'idInvestigador',
       'idAsistente',
       'idUsrAlta'
    ];
}
