<?php

namespace App\Http\Controllers\Catalogos;


date_default_timezone_set("America/Mexico_City");

use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Catalogos\catAutores;
use App\Models\Catalogos\catTipoAutor;

class autoresController extends Controller
{
    public function AutoresIndex(){
        $LcCad = "select idAutor, cNombre, 1 as idTipoAutor, 'Autor' as cTipoAutor, 'blue-grey darken-2' as cColor from cat_autores order by cNombre";
        $LcResp2 = DB::select($LcCad);

        /*$LcResp = catAutores::select(
            'idAutor',
            'cNombre',
            '"1" as idTipoAutor',
            '"Autor" as cTipoAutor',
            '"blue-grey darken-2" as cColor'
        )
        ->orderBy('cNombre')
        ->get();*/
        return $LcResp2;
    }

    public function TipoAutorList(){
        $LcResp = catTipoAutor::select(
            'idTipoAutor',
            'cTipoAutor',
            'cColor',
        )
        ->orderBy('cTipoAutor')
        ->get();
        return $LcResp;
    }

    public function tipoautorshow(){
        $LcResp = catTipoAutor::select(
            "cat_tipoautor.*",
            "users.name"
        )
        ->leftJoin("users", "cat_tipoautor.idUsrAlta", "=", "users.id")
        ->get();

        return $LcResp;
    }

    public function RegAutor(Request $request){
        $idUser = Auth::id();
        
    
        $new = catAutores::create([
            'cNombre' => $request->cNombre." ".$request->cApellido,
            'cFirstNombre' => $request->cNombre,
            'cApellido' => $request->cApellido,
            'cORCID' => $request->cORCID,
            'idUsrAlta' => $idUser
            
        ]);
       
    }

    public function showAutores(){
        $LcResp = catAutores::all();
        return $LcResp;
    }
}
