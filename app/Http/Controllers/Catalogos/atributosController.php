<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Catalogos\catAutores;
use App\Models\Catalogos\catTipoAutor;
use App\Models\Catalogos\catAtributos;
use App\Models\Catalogos\catTipoDoc;

class atributosController extends Controller
{
    public function AtrDelDoc($id){
        $LcCad = "select CA.idAtributo, CA.cAtributo as name, CA.cTipo as editor, ifnull(RDA.cValor,'') as value, RDA.idDocAtr, D.idDocumento
        from cat_atributos as CA
        inner join rel_tipodocatr as RTDA on CA.idAtributo = RTDA.idAtributo
        inner join documentos as D on RTDA.idTipoDoc = D.idTipoDoc
        left join rel_docatr as RDA on RDA.idDocumento = D.idDocumento and RDA.idAtributo = CA.idAtributo
        where D.idDocumento = ".$id;
        $LcResp = DB::select($LcCad);
        return $LcResp;
    }

    public function MostrarAtr(){
         /*$LcResp = catAtributos::select(
            "cat_atributos.idAtributo",
            "cat_atributos.cAtributo",
            "cat_atributos.cTipo",
            "cat_atributos.cEstatus",
            "cat_atributos.created_at",
            "users.name"
        )
        ->leftJoin("users", "cat_atributos.idUsrAlta", "=", "users.id")
        ->get();*/
        $LcCad = "select T1.*, T2.*, T3.*
        from
        (select idAtributo, count(*) as nDocs from rel_docatr group by idAtributo) as T1
        left join (select cat_atributos.*, users.name from cat_atributos left join users on cat_atributos.idUsrAlta = users.id) as T2 on T1.idAtributo=T2.idAtributo
        left join (select idAtributo, count(*) as nTipos from rel_tipodocatr group by idAtributo) as T3 on T1.idAtributo= T3.idAtributo";
        $LcResp = DB::select($LcCad);
        return $LcResp;
    }

    public function MostrarAtrAso(){
       
            $Atributos = catAtributos::get();
            $TipoDoc = catTipoDoc::get();
            $Total = array_merge(
                array('atributos' => $Atributos), 
                array('docs' => $TipoDoc)
            );
            return $Total;
  
   }

   public function AtrDelTipo($id){
        $Atributos = catAtributos::select(
            "cat_atributos.idAtributo",
            "cat_atributos.cAtributo",
            "cat_atributos.cTipo"
        )
        ->leftJoin("rel_tipodocatr", "cat_atributos.idAtributo", "=", "rel_tipodocatr.idAtributo")
        ->where('rel_tipodocatr.idTipoDoc', $id)
        ->where('cat_atributos.cEstatus', 'A')
        ->get();
        return $Atributos;
   }
}
