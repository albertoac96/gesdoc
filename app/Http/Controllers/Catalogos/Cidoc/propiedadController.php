<?php

namespace App\Http\Controllers\Catalogos\Cidoc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class propiedadController extends Controller
{
    public function showPropiedad(){
        $items = DB::table('cat_cidoc_props')->get();
        return $items;
    }
}
