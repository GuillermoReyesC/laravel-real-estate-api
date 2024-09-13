<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropiedadController extends Controller
{
    public function index()
    {
        return response()->json(Propiedad::all());
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'direccion' => 'required|string|max:255',
                'ciudad' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'precio' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $propiedad = Propiedad::create($request->all());
            return response()->json($propiedad, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar propiedad: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $propiedad = Propiedad::find($id);
        if ($propiedad) {
            return response()->json($propiedad);
        }
        return response()->json(['message' => 'Propiedad no encontrada'], 404);
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'direccion' => 'required|string|max:255',
                'ciudad' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'precio' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $propiedad = Propiedad::find($id);
            if ($propiedad) {
                $propiedad->update($request->all());
                return response()->json($propiedad);
            }
            return response()->json(['message' => 'Propiedad no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar propiedad: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $propiedad = Propiedad::find($id);
        if ($propiedad) {
            $propiedad->delete();
            return response()->json(['message' => 'Propiedad eliminada']);
        }
        return response()->json(['message' => 'Propiedad no encontrada'], 404);
    }
}