<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="Propiedad",
 *     type="object",
 *     title="Propiedad",
 *     description="Modelo de Propiedad",
 *     required={"direccion", "ciudad", "precio"},
 *     @OA\Property(
 *         property="direccion",
 *         type="string",
 *         example="Calle Falsa 123"
 *     ),
 *     @OA\Property(
 *         property="ciudad",
 *         type="string",
 *         example="Ciudad Ejemplo"
 *     ),
 *     @OA\Property(
 *         property="descripcion",
 *         type="string",
 *         example="Descripción de la propiedad"
 *     ),
 *     @OA\Property(
 *         property="precio",
 *         type="integer",
 *         example=100000
 *     )
 * )
 */
class PropiedadController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/propiedades",
     *     summary="Obtener todas las propiedades",
     *     tags={"Propiedad"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de propiedades",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Propiedad"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Propiedad::all());
    }

    /**
     * @OA\Post(
     *     path="/api/propiedades",
     *     summary="Crear una nueva propiedad",
     *     tags={"Propiedad"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Propiedad")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Propiedad creada",
     *         @OA\JsonContent(ref="#/components/schemas/Propiedad")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Errores de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", @OA\AdditionalProperties(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al guardar propiedad",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al guardar propiedad: mensaje del error")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/propiedades/{id}",
     *     summary="Obtener una propiedad por ID",
     *     tags={"Propiedad"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la propiedad",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Propiedad encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Propiedad")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Propiedad no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Propiedad no encontrada")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $propiedad = Propiedad::find($id);
        if ($propiedad) {
            return response()->json($propiedad);
        }
        return response()->json(['message' => 'Propiedad no encontrada'], 404);
    }

    /**
     * @OA\Put(
     *     path="/api/propiedades/{id}",
     *     summary="Actualizar una propiedad por ID",
     *     tags={"Propiedad"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la propiedad",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Propiedad")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Propiedad actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/Propiedad")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Propiedad no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Propiedad no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Errores de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", @OA\AdditionalProperties(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar propiedad",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al actualizar propiedad: mensaje del error")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/propiedades/{id}",
     *     summary="Eliminar una propiedad por ID",
     *     tags={"Propiedad"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la propiedad",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Propiedad eliminada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Propiedad eliminada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Propiedad no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Propiedad no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar propiedad",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al eliminar propiedad: mensaje del error")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $propiedad = Propiedad::find($id);
            if ($propiedad) {
                $propiedad->delete();
                return response()->json(['message' => 'Propiedad eliminada']);
            }
            return response()->json(['message' => 'Propiedad no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar propiedad: ' . $e->getMessage()], 500);
        }
    }
}