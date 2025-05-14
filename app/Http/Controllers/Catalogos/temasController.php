<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Catalogos\catTemas;

use App\Traits\Globales;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class temasController extends Controller
{
    public function cboTemas(){
        $LcResp = catTemas::orderBy('cTema')
        ->get();
        return $LcResp;
    }

    public function showTemas(){
        /*$LcResp = catTemas::select(
            "temas.idTema",
            "temas.cTema",
            "temas.cDescTema",
            "temas.cColor",
            "temas.cEstatus",
            "temas.created_at",
            "users.name"
        )
        ->leftJoin("users", "temas.idUsrAlta", "=", "users.id")
        ->get();*/

        $LcCad = "select T1.*, T2.*
        from
        (select idTema, count(*) as nDocs
        from rel_doctema
        group by idTema) as T1
        left join (select temas.idTema, temas.cTema, temas.cDescTema, temas.cColor, temas.cEstatus, temas.created_at,
        users.name from temas
        left join users on temas.idUsrAlta = users.id) as T2 on T1.idTema=T2.idTema";

        $LcResp = DB::select($LcCad);

        return $LcResp;
    }
}
