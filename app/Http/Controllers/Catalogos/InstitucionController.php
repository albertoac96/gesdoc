<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Catalogos\catInstitucion;

class InstitucionController extends Controller
{
    // Listar registros
    public function index()
    {
        try {
            $instituciones = catInstitucion::all();
            return response()->json($instituciones, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al listar instituciones', 'message' => $e->getMessage()], 500);
        }
    }

    // Crear un nuevo registro
    public function store(Request $request)
    {
        $request->validate([
            'cNombre' => 'required|string|max:255',
        ]);

        try {
            $institucion = new catInstitucion();
            $institucion->cNombre = $request->cNombre;
            $institucion->cAbv = $request->cAbv;
            $institucion->save();

            return response()->json(['message' => 'Institución creada exitosamente', 'data' => $institucion], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear institución', 'message' => $e->getMessage()], 500);
        }
    }

    // Eliminar un registro
    public function destroy($id)
    {
        try {
            $institucion = catInstitucion::findOrFail($id);
            $institucion->delete();

            return response()->json(['message' => 'Institución eliminada exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar institución', 'message' => $e->getMessage()], 500);
        }
    }
}
