<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Catalogos\catTipoDoc;

use App\Traits\Globales;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TipoDocController extends Controller
{
    use Globales;

    public function cboTipoDoc(){
        $LcResp = catTipoDoc::select(
            'idTipoDoc as value',
            'cTipoDoc as text',
            'cIcono'
        )
        ->orderBy('text')
        ->get();
        return $LcResp;
    }

    public function TipoDocIndex(){
        /*$LcResp = catTipoDoc::select(
            'cat_tipodoc.*',
            'users.name',
            'users.cAvatar'
        )
        ->leftJoin("users", "users.id", "=", "cat_tipodoc.idUsrAlta")
        ->orderBy('cTipoDoc')
        ->get();*/

        $LcCad = "select T1.*, T2.*
        from
        (select idTipoDoc, count(*) as nDocs
        from documentos
        group by idTipoDoc) as T1
        left join (select cat_tipodoc.*, users.name from cat_tipodoc
        left join users on cat_tipodoc.idUsrAlta = users.id) as T2 on T1.idTipoDoc=T2.idTipoDoc";
        $LcResp = DB::select($LcCad);

        return $LcResp;
    }
}
