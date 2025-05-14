<?php

namespace App\Models\Seg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
       'id',
       'name',
       'guard_name',
       'idUsrAlta'
    ];
}
