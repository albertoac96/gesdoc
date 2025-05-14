<?php

namespace App\Http\Controllers\Catalogos\Cidoc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class entidadController extends Controller
{
    public function showEntidad(){
        $entidades = DB::table('cat_cidoc_ents')->get();
        return $entidades;
    }
}
