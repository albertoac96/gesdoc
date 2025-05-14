<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Seg\Roles;
use App\Models\Seg\Permisos;
use App\Models\Seg\relRolesPermisos;
use Illuminate\Support\Facades\Auth;

use App\Traits\Globales;

class SeguridadController extends Controller
{

    use Globales;

    public function verUsuarios(){
        $LcCad = "select T1.*, T3.guard_name as cRol, T3.id as idRol, T4.name as creado from
        users as T1
        left join model_has_roles as T2 on T1.id = T2.model_id
        left join roles as T3 on T2.role_id = T3.id
        left join users as T4 on T1.idUsrAlta = T4.id";
        $oUsers = DB::select($LcCad);

        $LcCad = "select id as idRol, guard_name as cRol from roles";
        $oRoles = DB::select($LcCad);

        $LcResp = array_merge(
            array('users' => $oUsers), 
            array('roles' => $oRoles)
        );

        return $LcResp;
    }

    public function deleteUsuario(){
        
    }

    public function editUsuario(){
        
    }

    public function verRoles(){
      
        $LcResp = Roles::get();

      

        return $LcResp;
    }

    public function traerPermisos(){

      $oMenu = $this->TraeMenu();
      
        $oAcciones = Permisos::where('tipo', '=', 'accion')->get();

        $LcCad = "select id as idRol, guard_name as cRol from roles";
        $oRoles = DB::select($LcCad);

        $LcResp = array_merge(
            array('menu' => $oMenu), 
            array('acciones' => $oAcciones),
            array('cboRoles' => $oRoles)
        );
       return $LcResp;

    }

    public function permRol($idRol){
       
        $LcCad = "select * from role_has_permissions as T1
        left join permissions as T2 on T1.permission_id = T2.id
        where T1.role_id = ".$idRol." and T2.tipo = 'menu'";
        $oPermisos = DB::select($LcCad);
        
        $menu = '[';
        if(count($oPermisos) > 0){
            for($i=0; $i < count($oPermisos); $i++){
                $menu = $menu.'"'.$oPermisos[$i]->name.'", ';
             }
             $menu = substr($menu, 0, -2);
        }
       
        
        $menu = $menu.']';

       

        

        return $menu;
    }

    public function permAccions($idRol){
        $LcCad = "select * from role_has_permissions as T1
        left join permissions as T2 on T1.permission_id = T2.id
        where T1.role_id = ".$idRol." and T2.tipo = 'accion'";
        $acciones = DB::select($LcCad);
        
       
        

        return $acciones;
    }

    public function editPermisos(Request $request){
        $PoAcciones = json_decode($request["acciones"]);
        $PoMenu = json_decode($request["menu"]);
        $PoRol = json_decode($request["rol"]);
        $idRol = $PoRol->idRol;

     

        relRolesPermisos::where('role_id', $idRol)->delete();

        if($PoAcciones->agregar == 1){
            $flight = new relRolesPermisos;

            $flight->permission_id = '1';
            $flight->role_id = $idRol;
    
            $flight->save();
          
            
        } 
        if($PoAcciones->editar == 1){
            relRolesPermisos::create([
                'permission_id' => '2',
                'role_id' => $idRol
            ]);
        }
        if($PoAcciones->papelera == 1){
            relRolesPermisos::create([
                'permission_id' => '3',
                'role_id' => $idRol
            ]);
        }
        if($PoAcciones->eliminar == 1){
            relRolesPermisos::create([
                'permission_id' => '4',
                'role_id' => $idRol
            ]);
        }
        if($PoAcciones->descargar == 1){
            relRolesPermisos::create([
                'permission_id' => '32',
                'role_id' => $idRol
            ]);
        }

       
        for($i=0; $i<count($PoMenu); $i++){
            $permiso = Permisos::where('name', '=', $PoMenu[$i])->get();
           
            relRolesPermisos::create([
                'permission_id' => $permiso[0]->id,
                'role_id' => $idRol
            ]);
        }

        return "OK";
    }

    public function tienePermiso(Request $request){
        
        $idUser = Auth::id();
        $LcCad = "select T2.id, T2.name from role_has_permissions as T1
        left join permissions as T2 on T1.permission_id = T2.id
        left join roles as T3 on T1.role_id = T3.id
        left join model_has_roles as T4 on T3.id = T4.role_id
        left join users as T5 on T4.model_id = T5.id
        where T5.id = ".$idUser;
        $permisos = DB::select($LcCad);
        $existe = 0;
        for($i=0; $i<count($permisos); $i++){
            if($permisos[$i]->name == $request->permiso){
                $existe = 1;
                break;
            } else {
                $existe = 0;
            }
        }
        if($existe == 1){
            return true;
        } else {
            return false;
        }
        
    }

    public function listaSesiones(){
        $sessions = DB::table('sessions')
            ->where('user_id', auth()->id())
            ->orderBy('last_activity', 'DESC')
            ->get();
        return $sessions;
    }

    public function deleteSesion(Request $request){
        DB::table('sessions')
        ->where('id', $request->id)
        ->where('user_id', auth()->id())
        ->delete();

        return 1;
    }
}
