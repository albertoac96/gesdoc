<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Catalogos\catPublicaciones;

use App\Traits\Globales;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class publicacionesController extends Controller
{

    use Globales;

    public function PeriodicasIndex($id){
        $idCat = 0;
        if($id == 1 || $id == 15 || $id == 14 || $id == 16) $idCat = 1;
        if($id == 4) $idCat = 2;

        $LcResp = catPublicaciones::select(
            'idPublicacion as value',
            'cPublicacion as text',
        )
        ->where('idCat_TipoPub', '=', $idCat)
        ->orderBy('text')
        ->get();
        return $LcResp;
    }

    public function traerPubs(){
        $LcCad = "select T1.*, T2.*, T3.cTipoPub
        from
        (select idPublicacion, count(*) as nDocs from rel_pubdoc group by idPublicacion) as T1
        left join (select cat_publicaciones.*, users.name from cat_publicaciones 
        left join users on cat_publicaciones.idUsrAlta = users.id) as T2 on T1.idPublicacion=T2.idPublicacion
        left join cat_tipopub as T3 on T2.idCat_TipoPub = T3.idCatTipoPub";
        $LcResp = DB::select($LcCad);
        return $LcResp;
    }
}
