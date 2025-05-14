<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Catalogos\catLugares;

class lugarController extends Controller
{
     // Listar registros
    public function index()
    {
        try {
            $grupos = catLugares::all();
            return response()->json($grupos, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al listar grupos', 'message' => $e->getMessage()], 500);
        }
    }

    // Crear un nuevo registro
    public function store(Request $request)
    {
        $request->validate([
            'cNombre' => 'required|string|max:255',
        ]);

        try {
            $grupo = new catLugares();
            $grupo->cNombre = $request->cNombre;
            $grupo->cLat = $request->cLat;
            $grupo->cLong = $request->cLong;
            $grupo->save();

            return response()->json(['message' => 'Grupo creada exitosamente', 'data' => $grupo], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear grupo', 'message' => $e->getMessage()], 500);
        }
    }

    // Eliminar un registro
    public function destroy($id)
    {
        try {
            $grupo = catLugares::findOrFail($id);
            $grupo->delete();

            return response()->json(['message' => 'Grupo eliminada exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar grupo', 'message' => $e->getMessage()], 500);
        }
    }
}
