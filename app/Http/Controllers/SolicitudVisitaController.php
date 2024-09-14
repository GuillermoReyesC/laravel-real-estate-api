<?php

namespace App\Http\Controllers;

use App\Models\SolicitudVisita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="SolicitudVisita",
 *     type="object",
 *     title="Solicitud de Visita",
 *     description="Modelo de Solicitud de Visita",
 *     required={"persona_id", "propiedad_id", "fecha_visita"},
 *     @OA\Property(
 *         property="persona_id",
 *         type="integer",
 *         example=14
 *     ),
 *     @OA\Property(
 *         property="propiedad_id",
 *         type="integer",
 *         example=10
 *     ),
 *     @OA\Property(
 *         property="fecha_visita",
 *         type="string",
 *         format="date",
 *         example="2024-09-12"
 *     ),
 *     @OA\Property(
 *         property="comentarios",
 *         type="string",
 *         example="Comentario sobre la visita"
 *     )
 * )
 */

class SolicitudVisitaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/solicitudes",
     *     summary="Obtener todas las solicitudes de visita",
     *     tags={"SolicitudVisita"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de solicitudes de visita",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/SolicitudVisita"))
     *     )
     * )
     */
    public function index()
    {
        $solicitudes = SolicitudVisita::with('persona', 'propiedad')->get();

        $formattedSolicitudes = $solicitudes->map(function($solicitud) {
            return [
                'id' => $solicitud->id,
                'persona' => $solicitud->persona->nombre,
                'propiedad' => $solicitud->propiedad->direccion,
                'fecha_visita' => $solicitud->fecha_visita,
                'comentarios' => $solicitud->comentarios,
            ];
        });

        return response()->json($formattedSolicitudes);
    }

    /**
     * @OA\Post(
     *     path="/api/solicitudes",
     *     summary="Crear una nueva solicitud de visita",
     *     tags={"SolicitudVisita"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SolicitudVisita")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Solicitud de visita creada",
     *         @OA\JsonContent(ref="#/components/schemas/SolicitudVisita")
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
     *         description="Error al guardar solicitud de visita",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al guardar solicitud de visita: mensaje del error")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'personaId' => 'required|exists:personas,id',
                'propiedadId' => 'required|exists:propiedades,id',
                'fechaVisita' => 'required|date',
                'comentarios' => 'nullable|string|max:255',
            ]);

            $solicitud = SolicitudVisita::create([
                'persona_id' => $validated['personaId'],
                'propiedad_id' => $validated['propiedadId'],
                'fecha_visita' => $validated['fechaVisita'],
                'comentarios' => $validated['comentarios'],
            ]);

            return response()->json($solicitud, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar solicitud de visita: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/solicitudes/{id}",
     *     summary="Obtener una solicitud de visita por ID",
     *     tags={"SolicitudVisita"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la solicitud de visita",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Solicitud de visita encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/SolicitudVisita")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud de visita no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Solicitud de visita no encontrada")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $solicitud = SolicitudVisita::with('persona', 'propiedad')->findOrFail($id);

        $formattedSolicitud = [
            'id' => $solicitud->id,
            'persona' => $solicitud->persona->nombre,
            'propiedad' => $solicitud->propiedad->direccion,
            'fecha_visita' => $solicitud->fecha_visita,
            'comentarios' => $solicitud->comentarios,
        ];

        return response()->json($formattedSolicitud);
    }

    /**
     * @OA\Put(
     *     path="/api/solicitudes/{id}",
     *     summary="Actualizar una solicitud de visita por ID",
     *     tags={"SolicitudVisita"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la solicitud de visita",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SolicitudVisita")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Solicitud de visita actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/SolicitudVisita")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud de visita no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Solicitud de visita no encontrada")
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
     *         description="Error al actualizar solicitud de visita",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al actualizar solicitud de visita: mensaje del error")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'persona_id' => 'required|exists:personas,id',
                'propiedad_id' => 'required|exists:propiedades,id',
                'fecha_visita' => 'required|date',
                'comentarios' => 'nullable|string|max:255',
            ]);

            $solicitud = SolicitudVisita::findOrFail($id);
            $solicitud->update($validated);

            return response()->json($solicitud);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar solicitud de visita: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/solicitudes/{id}",
     *     summary="Eliminar una solicitud de visita por ID",
     *     tags={"SolicitudVisita"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la solicitud de visita",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Solicitud de visita eliminada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Solicitud de visita eliminada correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud de visita no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Solicitud de visita no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar solicitud de visita",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al eliminar solicitud de visita: mensaje del error")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $solicitud = SolicitudVisita::findOrFail($id);
            $solicitud->delete();

            return response()->json(['message' => 'Solicitud de visita eliminada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar solicitud de visita: ' . $e->getMessage()], 500);
        }
    }
}
