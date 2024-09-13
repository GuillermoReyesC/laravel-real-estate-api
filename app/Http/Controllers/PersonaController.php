<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    public function index()
    {
        return response()->json(Persona::all());
    }

    public function store(Request $request)
    {
        $persona = Persona::create($request->all());
        return response()->json($persona, 201);
    }

    public function show($id)
    {
        $persona = Persona::find($id);
        if ($persona) {
            return response()->json($persona);
        }
        return response()->json(['message' => 'Persona no encontrada'], 404);
    }

    public function update(Request $request, $id)
    {
        $persona = Persona::find($id);
        if ($persona) {
            $persona->update($request->all());
            return response()->json($persona);
        }
        return response()->json(['message' => 'Persona no encontrada'], 404);
    }

    public function destroy($id)
    {
        $persona = Persona::find($id);
        if ($persona) {
            $persona->delete();
            return response()->json(['message' => 'Persona eliminada']);
        }
        return response()->json(['message' => 'Persona no encontrada'], 404);
    }
}
