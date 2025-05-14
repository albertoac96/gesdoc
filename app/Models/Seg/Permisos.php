<?php

namespace App\Models\Seg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    use HasFactory;
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $fillable = [
       'id',
       'name',
       'guard_name',
       'tipo'
    ];
}
