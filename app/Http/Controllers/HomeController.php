<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Seg\relUserRoles;
use App\Models\User;
use App\Models\Seg\Permisos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Mail\newUser;
use App\Mail\newUserToAdmin;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('welcome');
    }

    public function getAllPermissionsAttribute() {
        $permissions = [];

       
        $idRole = DB::select("select role_id from model_has_roles where model_id = ".Auth::user()->id);
        $idRole = $idRole[0]->role_id;

        $permisosdb = DB::select("select T2.name
        from role_has_permissions as T1
        left join permissions as T2 on T1.permission_id = T2.id
        where role_id = ".$idRole);
        
        $cPermisos = "";
          foreach ($permisosdb as $permission) {
            $permiso = $permission->name;
            $cPermisos = $cPermisos." ".$permiso;
          }
          return $cPermisos;
      }

      public function InfoUserPerfil(){
        $id = Auth::id();
        $usuario = User::select(
            "name as user",
            "email",
            "cAvatar",
            "id as Cve"
        )
        ->where("id", "=", $id)
        ->get();
        return $usuario[0];
       }
        

      public function InfoUser(){
        $idUser = Auth::id();

        $LcInfo = User::select(
            "name as user",
            "email as email",
            "cAvatar as avatar",
            "id as Cve"
        )
        ->where("id", "=", $idUser)
        ->get();

        $LcRol = User::select(
            "model_has_roles.role_id",
            "roles.guard_name"
        )
        ->leftJoin("model_has_roles", "users.id", "=", "model_has_roles.model_id")
        ->leftJoin("roles", "model_has_roles.role_id", "=", "roles.id")
        ->where("users.id", "=", $idUser)
        ->get();

        $LidRol = $LcRol[0]->role_id;

        //$LcRol = $LcRol[0]->guard_name;
        //$LcInfo = array_merge($LcInfo, array('rol' => $LcRol));
        //return $LcInfo;

        $LcPermisos = Permisos::select(
            
            "permissions.name"
        )
        ->leftJoin("role_has_permissions", "permissions.id", "=", "role_has_permissions.permission_id")
        ->where("role_has_permissions.role_id", "=", $LidRol)
        ->get();

        //$LcCad = "select T1.name from permissions as T1 left join role_has_permissions as T2 on T1.id = T2.permission_id where T2.role_id = ".$LidRol;
        //$Permisos = DB::select($LcCad);
        //return $LcPermisos;

        $Permisos = array();
        for ($i = 0; $i < count($LcPermisos); $i++) {
            $name = $i;
            $permiso = array($name => $LcPermisos[$i]->name);
            
            $Permisos = array_merge($Permisos, $permiso);
        }
       //$Permisos = $Permisos." }]";
        //return $Permisos;

        $Total = array_merge(array('info' => $LcInfo[0]), 
                    array('rol' => $LcRol[0]->guard_name),  
                    array('permisos' => $Permisos),  
                );
        return $Total;


       // $LcPermisos = DB::select()
      }

      public function newUser(Request $request){
        $infoUser = json_decode($request["item"]);

        $existe = User::where('email', $infoUser->email)->get();
        if(count($existe)>0){
            return 1;
        }
        
        $comb = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $shfl = str_shuffle($comb);
        $pass = substr($shfl,0,8);

        $passHash = Hash::make($pass);

        $newUser = User::create([
            'name' => $infoUser->name,
            'email' => $infoUser->email,
            'password' => $passHash,
            'idUsrAlta' => Auth::id()
        ]);

        $Rol = relUserRoles::create([
            'role_id' => $infoUser->cRol->idRol,
            'model_id' => $newUser->id,
        ]);


    
        Mail::to($infoUser->email)->send(new newUser($infoUser, $pass, $request["url"]));
        $emailAdmin = DB::select("select email from users where id = ".Auth::id());

       
        Mail::to($emailAdmin[0])->send(new newUserToAdmin($infoUser, $pass, $request["url"]));
        
        $LcCad = "select T1.*, T3.guard_name as cRol, T3.id as idRol, T4.name as creado from
        users as T1
        left join model_has_roles as T2 on T1.id = T2.model_id
        left join roles as T3 on T2.role_id = T3.id
        left join users as T4 on T1.idUsrAlta = T4.id
        where T1.id = ".$newUser->id;
        $Usuario = DB::select($LcCad);

        return ($Usuario[0]);
      }

      public function editUser(Request $request){
        

        $existe = User::where('email', $request->email)
        ->where('id', '!=', $request->id)
        ->get();
        if(count($existe)>0){
            return 1;
        }

        User::where('id',$request->id)
        ->update(['name'=>$request->name], ['email'=>$request->email]);

        relUserRoles::where('model_id',$request->id)
        ->update(['role_id'=>$request->cRol->idRol]);

        return 0;

      }

   
    
}
