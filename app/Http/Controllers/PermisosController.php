<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permisos;
use Illuminate\Support\Facades\DB;

class PermisosController extends Controller
{
    public function index(){
        $permisos = Permisos::all();
        return $permisos;
    }

    public function permisosPerfil($id){
        $permisos = DB::select("select T2.* from 
        role_has_permissions as T1
        left join permissions as T2 on T1.permission_id = T2.id
        where role_id = ".$id);
        return $permisos;
    }
}
