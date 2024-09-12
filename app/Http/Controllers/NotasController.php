<?php

namespace App\Http\Controllers;


date_default_timezone_set("America/Mexico_City");

use Illuminate\Http\Request;
use App\Models\Docs\NotasArchivos;
use App\Traits\Globales;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotasController extends Controller
{
    use Globales;

    public function DeleteNota(){
        return $this->metodoComun();
    }
    public function TraeNotasDelArchivo($id){

        $LcCad = "select 
        T1.idNotaArchivo as idNota, T1.cNota as titulo, T1.cContenido as contenido, T1.iPagina as pagina, T1.created_at, T1.updated_at, T2.id as idUsrAlta, T2.name as user, T2.cAvatar as avatar
        from 
        notas_archivos as T1
        left join users as T2 on T1.idUsrAlta = T2.id
        where T1.idArchivo = ".$id."
        and T1.cEstatus = 'A'";
        
        $LcResp = NotasArchivos::select(
            "notas_archivos.idNotaArchivo as idNota",
            "notas_archivos.cNota as titulo",
            "notas_archivos.cContenido as contenido",
            "notas_archivos.iPagina as pagina",
            "notas_archivos.created_at",
            "notas_archivos.updated_at",
            "users.id as idUsrAlta",
            "users.name as user",
            "users.cAvatar as avatar"

        )
        ->leftJoin("users", "users.id", "=", "notas_archivos.idUsrAlta")
        ->where("notas_archivos.idArchivo", "=", $id)
        ->where("notas_archivos.cEstatus", "=", "A")
        ->get();

        return DB::select($LcCad);
    }

    public function RegistraNota(Request $request){
        
        $PcTitulo = $request["titulo"];
        $PcContenido = $request["contenido"];
        $PdAlta = $request["created_at"];
        $PidUser = $request["idUsr"];
        $PiPag = $request["pagina"];
        $PidArchivo = $request["idArchivo"];
        $idUser = Auth::id();

        //return date('m-d-Y h:i:s a', time());  

        $nota = NotasArchivos::create([
            'cNota' => $PcTitulo,
            'cContenido' => $PcContenido,
            'idArchivo' => $PidArchivo,
            'iPagina' => $PiPag,
            'idUsrAlta' => $idUser,
            'cEstatus' => "A",
        ]);

        $idNota = $nota->idNotaArchivo;
        
        $Archivo = DB::select("select * from rel_archivosdoc where idRelArDoc = ".$PidArchivo);
        
        $this->RegistraCambio('Se creo una nota en archivo asociado', $Archivo[0]->idDocumento, $Archivo[0]->cNombre);
        //$dAlta = $nota->created_at;

        /*$newNota = NotasArchivos::find($idNota);
        $newNota->updated_at = $dAlta;
        $newNota->save();*/

        return $idNota;
    }

    public function EditaNota(Request $request){
        $PidNota = $request["idNota"];
        $PcTitulo = $request["titulo"];
        $PcContenido = $request["contenido"];

        $nota = NotasArchivos::find($PidNota);
        $idUsrAlta = $nota->idUsrAlta;
        $idUser = Auth::id();
        if($idUser != $idUsrAlta){
            return 0;
        }

        $nota->cNota = $PcTitulo;
        $nota->cContenido = $PcContenido;
        $nota->save();

        return 1;


    }
}
