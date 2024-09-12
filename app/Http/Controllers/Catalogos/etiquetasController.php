<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

date_default_timezone_set("America/Mexico_City");

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Catalogos\catEtiquetas;



use App\Traits\Globales;

class etiquetasController extends Controller
{
    use Globales;

    public function RegTag(Request $request){
        $idUser = Auth::id();
        $Tag = $request->name;
        $new = catEtiquetas::create([
            'cEtiqueta' => $Tag,
            'idUsrAlta' => $idUser,
            
        ]);        
        return $new;
    }

}
