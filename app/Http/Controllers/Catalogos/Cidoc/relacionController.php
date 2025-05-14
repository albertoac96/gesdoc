<?php

namespace App\Http\Controllers\Catalogos\Cidoc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class relacionController extends Controller
{
    public function showRel(){
        $LcCad = 'select T1.idRel, T1.cNombre, T2.CveEntidad as EntOrigenID, T2.cNombre as EntOrigen, 
        T4.CvePropiedad, T4.cNombre as cPropiedad, T3.CveEntidad as EntDestinoID, T3.cNombre as EntDestino from cat_cidoc_rels as T1 
        left join cat_cidoc_ents as T2 on T1.idEntidad = T2.idEntidad
        left join cat_cidoc_ents as T3 on T1.idEntidadRel = T3.idEntidad
        left join cat_cidoc_props as T4 on T1.idProp = T4.idPropiedad';

        $items = DB::select($LcCad);
        return $items;
    }

    public function editRel(Request $request){
        if(!$request->idRel){
            DB::table('cat_cidoc_rels')->insert([
                'cNombre' => $request->nombre,
                'idEntidad' => $request->origen['idEntidad'],
                'idProp' => $request->propiedad['idPropiedad'],
                'idEntidadRel' => $request->destino['idEntidad']
            ]);
        } else {
            DB::table('cat_cidoc_rels')
            ->where('idRel', $request->idRel)
            ->update([
                'cNombre' => $request->nombre,
                'idEntidad' => $request->origen['idEntidad'],
                'idProp' => $request->propiedad['idPropiedad'],
                'idEntidadRel' => $request->destino['idEntidad']
            ]);
        }
        return 'OK';
    }

    
}
