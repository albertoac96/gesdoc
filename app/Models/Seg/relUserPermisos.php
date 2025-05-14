<?php

namespace App\Models\Seg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class relUserPermisos extends Model
{
    use HasFactory;
    protected $table = 'model_has_permissions';
    protected $primaryKey = 'permission_id';
    public $timestamps = false;

    protected $fillable = [
        'permission_id',
        'model_type',
        'model_id'
    ];
}
