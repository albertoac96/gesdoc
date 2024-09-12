<?php

namespace App\Models\Seg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class relRolesPermisos extends Model
{
    use HasFactory;
    protected $table = 'role_has_permissions';
    public $timestamps = false;
    protected $fillable = [
       'permission_id',
       'role_id',
    ];
}
