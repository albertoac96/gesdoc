<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class eventosController extends Controller
{
    public function verEventosDeAutor($id){
        $autor = DB::table('cat_autores')->where('idAutor', $id)->first();

        $grafo = DB::table('cat_cidoc_grafos')->where('idActorRel', $id)->first();
        $grafo = json_decode($grafo->cContenido);

        /*if ($grafo != ''){
            return $grafo;
        }*/
        
        $nodes = [];
        $links = [];
        $nodes[] = [
            'id' => $autor->idAutor,
            'name' => $autor->cFirstNombre." ".$autor->cApellido,
            'type' => $autor->idAutor,
            'cidoc' => 'E21',
            'date' => ''
        ];
        $eventos = DB::table('cat_cidoc_events')->where('cOrigen', 'E21')->where('idOrigen', $id)->get();
        
        //return $eventos;
        /*foreach ($eventos as $evento) {
            $nodes[] = [
                'id' => $evento->idEvento,
                'name' => $evento->cEvento,
                'type' => $evento->cEnlace
            ];
            $links[] = [
                'source' => 0,
                'target' => $evento->idEvento,
                'name' => $evento->cEnlace
            ];
        }*/
        
        return response()->json([
            'nodes' => $nodes,
            'links' => $links
        ]);
        return $autor;
    }

    public function guardarEvento(Request $request){
        $clave = $request->relacion['EntDestinoID'];
        $idDestino = null;
        $cDestino = $request->cDestino;

        if($clave == 'E21'){ //PERSONAS
            $autor = DB::table('cat_autores')->where('cNombre', $cDestino)->first();
            
            if($autor === ''){
                DB::table('cat_autores')->insert([
                    'cNombre' => $autor
                ]);
            }
            $autor = DB::table('cat_autores')->where('cNombre', $cDestino)->first();
            
            $idDestino = $autor->idAutor;
            $cDestino = $request->relacion['EntDestinoID'];
       } else if($clave == 'E53') { //LUGARES
            $autor = DB::table('cat_lugares')->where('cNombre', $cDestino)->first();
            if($autor === ''){
                DB::table('cat_lugares')->insert([
                    'cNombre' => $autor
                ]);
            }
            $autor = DB::table('cat_lugares')->where('cNombre', $cDestino)->first();
            $idDestino = $autor->idLugar;
            $cDestino = $request->relacion['EntDestinoID'];
       } else if($clave == 'E39') { //ACTOR (INSTITUCION)
            $autor = DB::table('cat_instituciones')->where('cNombre', $cDestino)->first();
            if($autor === ''){
                DB::table('cat_instituciones')->insert([
                    'cNombre' => $autor
                ]);
            }
            $autor = DB::table('cat_instituciones')->where('cNombre', $cDestino)->first();
            $idDestino = $autor->idInstitucion;
            $cDestino = $request->relacion['EntDestinoID'];
       } else {
            $cDestino = $request->relacion['EntDestinoID'];
       }
       
       if($request->idPadre == 0){
        $idRelPadre = $request->idOrigen;
       } else {
        $idRelPadre = $request->idPadre;
       }
       
       DB::table('cat_cidoc_events')->insert([
            'idEntidadPadre' => $request->idPadre,
            'idRelPadre' => $idRelPadre,
            'cEnlace' => $request->enlace,
            'idEntidadRel' => $request->relacion['EntDestinoID'],
            'idRelHijo' => 0,
            'cEvento' => $request->nombre,
            'cDescripcion' => $request->desc,
            'dFechaInicio' => $request->dInicio,
            'dFechaFin' => $request->dFin,
            'idOrigen' => $request->idOrigen,
            'cOrigen' => $request->relacion['EntOrigenID'],
            'idRelacionCidoc' => $request->relacion['idRel'],
            'idTipo' => $request->tipo['idTipo'],
            'idDestino' => $idDestino,
            'cDestino' => $cDestino,
            'cTermino' => $request->cDestino
        ]);

        return "OK";
    }

    public function eventosDeAutorShow($id){
        $eventos = DB::table('cat_cidoc_events')->where('cOrigen', 'E21')->where('idOrigen', $id)->get();
        return $eventos;
    }

    public function tiposEventos(){
        $items = DB::table('cat_eventos_tipos')->get();
        return $items;
    }

    public function verEventosDe($idEvento){
        $item = explode("_", $idEvento);
        $eventos = DB::table('cat_cidoc_relaciones')->where('cveSource', $item[0])->where('idSource', $item[1])->get();
        return $eventos;
        $eventos = DB::table('cat_cidoc_events')->where('idRelPadre', $idEvento)->get();
        return $eventos;
    }

    public function traeNodo($idNodo){
        $item = explode("_", $idNodo);
        $evento = DB::table('cat_cidoc_eventos')->where('idEvento', $item[1])->first();
        return $evento;
    }

    public function infoDelEvento($idEvento){
        $item = explode("_", $idEvento);
        $eventos = DB::table('cat_cidoc_relaciones')->where('cveSource', $item[0])->where('idSource', $item[1])->get();
        return $eventos;
    }

    public function guardarRed(Request $request){
        //return $request;
        $item = DB::table('cat_cidoc_grafos')->where('idActorRel', $request->id)->first();
        //return $item;
        if($item === ''){
            DB::table('cat_cidoc_grafos')->insert([
                'cNombre' => 'prueba',
                'idActorRel' => $request->id,
                'cContenido' => json_encode($request->params)
            ]);
        } else {
            DB::table('cat_cidoc_grafos')->where('idActorRel', $request->id)->update([
                'cContenido' => json_encode($request->params)
            ]);
        }
        
        return "OK";
    }

    public function traeActoresDe($id){
       
        switch ($id) {
            case 'E21':
                $idColumn = 'idAutor as id';
                $table = 'cat_autores';
                break;
        
            case 'E39':
                $idColumn = 'idInstitucion as id';
                $table = 'cat_instituciones';
                break;
        
            // Agregar más casos según sea necesario
            case 'E53':
                $idColumn = 'idLugar as id';
                $table = 'cat_lugares';
                break;
            
            case 'E74':
                $idColumn = 'idPublicacion as id';
                $table = 'cat_publicaciones';
                break;
        }

        $items = DB::table($table)
            ->select(
                $idColumn,
                $table === 'cat_publicaciones' ? 'cPublicacion as cNombre' : 'cNombre'
            )
            ->where('cEstatus', 'A')
            ->get();
        return $items;
    }

    public function verSugerencias(Request $request)
    {
        $query = $request->query('query');
        $entDestino = $request->query('entdestino');

        // Obtener el parámetro 'query' de la cadena de consulta
        $query = $request->query('query');

        if($entDestino == 'E21'){ //PERSONAS
             // Consultar la base de datos para buscar ciudades que coincidan parcialmente con el query
            $sugerencias = DB::table('cat_autores')
            ->where('cNombre', 'LIKE', '%' . $query . '%')  // Coincidencias parciales
            ->pluck('cNombre');  // Obtener solo la columna 'nombre'
        } else if($entDestino == 'E53') { //LUGARES
            $sugerencias = DB::table('cat_lugares')
            ->where('cNombre', 'LIKE', '%' . $query . '%')  // Coincidencias parciales
            ->pluck('cNombre');  // Obtener solo la columna 'nombre'
        } else if($entDestino == 'E39') { //ACTOR (INSTITUCION)
            $sugerencias = DB::table('cat_instituciones')
            ->where('cNombre', 'LIKE', '%' . $query . '%')  // Coincidencias parciales
            ->pluck('cNombre');  // Obtener solo la columna 'nombre'
        } else {
             // Consultar la base de datos para buscar ciudades que coincidan parcialmente con el query
            $sugerencias = DB::table('cat_cidoc_events')
            ->where('cTermino', 'LIKE', '%' . $query . '%')  // Coincidencias parciales
            ->pluck('cTermino');  // Obtener solo la columna 'nombre'
        }
       

        // Devolver las sugerencias en formato JSON
        return response()->json($sugerencias);
    }


}
