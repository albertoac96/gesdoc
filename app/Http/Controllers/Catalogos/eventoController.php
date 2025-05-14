<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Catalogos\catEventos;
use App\Models\Catalogos\relEventosDocs;

use App\Models\Catalogos\catAutores;
use App\Models\Catalogos\catInstitucion;
use App\Models\Catalogos\catPublicaciones;
use App\Models\Catalogos\catGrupos;
use App\Models\Catalogos\catLugares;

use Illuminate\Support\Facades\Auth;

class eventoController extends Controller
{
    // Listar registros
    public function index($id, $tipo)
    {
        
        try {
            $items = catEventos::where('idActor', $id)->where('idTipoActor', $tipo)->get();

            

            foreach ($items as $evento) {
                $actor = null;
                $nombre = null;

               

                switch ($evento->idTipoActorRel) {
                    case 1: // Autor
                        $actor = catAutores::find($evento->idActorRel);
                        $nombre = $actor ? $actor->cNombre : null;
                        break;
                    case 2: // Institución
                        $actor = catInstitucion::find($evento->idActorRel);
                        $nombre = $actor ? $actor->cNombre : null;
                        break;
                    case 3: // Publicación
                        $actor = catPublicaciones::find($evento->idActorRel);
                        $nombre = $actor ? $actor->cPublicacion : null;
                        break;
                    case 4: // Grupo
                        $actor = catGrupos::find($evento->idActorRel);
                        $nombre = $actor ? $actor->cNombre : null;
                        break;
                    case 5: // Lugar
                        $actor = catLugares::find($evento->idActorRel);
                        $nombre = $actor ? $actor->cNombre : null;
                        break;
                }

                // Agregar el campo personalizado
                $evento->actorRelacionado = $actor
                    ? ['id' => $actor->getKey(), 'nombre' => $nombre]
                    : null;
            }

            return response()->json($items->toArray(), 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al listar grupos', 'message' => $e->getMessage()], 500);
        }
    }

    // Crear un nuevo registro
    public function store(Request $request)
    {
       $idTipoActorRel = $request->tipoActorRel;
       $idActorRel = $request->actorRelacionado;

       if(is_string($idActorRel)){
        //Hay que agregar el actor
        return "El actor es una string";
       } else {
        //El actor se guarda como con el id
        $idActorRel = $request->actorRelacionado['id'];
       }

        try {
            $grupo = new catEventos();
            $grupo->idTipoEvento = $request->tipoEvento;
            $grupo->cRelacion = $request->tipoRelacion;
            $grupo->idActor = $request->idActor;
            $grupo->idTipoActor = $request->idTipoActor;
            $grupo->idTipoActorRel = $request->tipoActorRel;
            $grupo->idActorRel = $idActorRel;
            $grupo->cDescripcion = $request->descripcion;
            $grupo->dFecha = $request->fecha;
            $grupo->idUsrAlta = Auth::id();
            $grupo->save();

            return response()->json(['message' => 'evento creada exitosamente', 'data' => $grupo], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear evento', 'message' => $e->getMessage()], 500);
        }
    }

    // Eliminar un registro
    public function destroy($id)
    {
        try {
            $grupo = catEventos::findOrFail($id);
            $grupo->delete();

            return response()->json(['message' => 'evento eliminada exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar evento', 'message' => $e->getMessage()], 500);
        }
    }


    public function traeActoresDe($id){
       
        switch ($id) {
            case '1':
                $idColumn = 'idAutor as id';
                $table = 'cat_autores';
                break;
        
            case '2':
                $idColumn = 'idInstitucion as id';
                $table = 'cat_instituciones';
                break;
        
            // Agregar más casos según sea necesario
            case '5':
                $idColumn = 'idLugar as id';
                $table = 'cat_lugares';
                break;
            
            case '3':
                $idColumn = 'idPublicacion as id';
                $table = 'cat_publicaciones';
                break;
            
            case '4':
                $idColumn = 'idGrupo as id';
                $table = 'cat_grupos';
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

    public function verEventosDe($id, $tipo){
        try {
            $items = catEventos::where('idTipoActor', $tipo)
                ->where('idActor', $id)
                ->orderBy('dFecha', 'desc')
                ->get();
            return response()->json($items, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al listar eventos', 'message' => $e->getMessage()], 500);
        }
    }
}
