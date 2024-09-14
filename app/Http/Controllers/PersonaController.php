<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Persona",
 *     type="object",
 *     title="Persona",
 *     description="Modelo de Persona",
 *     required={"nombre", "email","direccion"},
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         example="Juan Perez"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         example="juan@example.com"
 *     ),
 *     @OA\Property(
 *         property="telefono",
 *         type="string",
 *         example=12345678
 *     ),
 * 
 * )
 */
class PersonaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/personas",
     *     summary="Obtener todas las personas",
     *     tags={"Persona"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de personas",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Persona"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Persona::all());
    }

    /**
     * @OA\Post(
     *     path="/api/personas",
     *     summary="Crear una nueva persona",
     *     tags={"Persona"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Persona")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Persona creada",
     *         @OA\JsonContent(ref="#/components/schemas/Persona")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $persona = Persona::create($request->all());
        return response()->json($persona, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/personas/{id}",
     *     summary="Obtener una persona por ID",
     *     tags={"Persona"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la persona",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Persona")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Persona no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Persona no encontrada")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $persona = Persona::find($id);
        if ($persona) {
            return response()->json($persona);
        }
        return response()->json(['message' => 'Persona no encontrada'], 404);
    }

   /**
     * @OA\Put(
     *     path="/api/personas/{id}",
     *     summary="Actualizar una persona por ID",
     *     tags={"Persona"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la persona",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Persona")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/Persona")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Persona no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Persona no encontrada")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $persona = Persona::find($id);
        if ($persona) {
            $persona->update($request->all());
            return response()->json($persona);
        }
        return response()->json(['message' => 'Persona no encontrada'], 404);
    }

    /**
     * @OA\Delete(
     *     path="/api/personas/{id}",
     *     summary="Eliminar una persona por ID",
     *     tags={"Persona"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la persona",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona eliminada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Persona eliminada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Persona no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Persona no encontrada")
     *         )
     *     )
     * )
     */
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
