<?php

namespace App\Http\Controllers;

use App\Models\SolicitudVisita;
use Illuminate\Http\Request;

class SolicitudVisitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'personaId' => 'required|exists:personas,id',
            'propiedadId' => 'required|exists:propiedades,id',
            'fechaVisita' => 'required|date',
            'comentarios' => 'nullable|string|max:255',
        ]);
    
        // Crear la solicitud de visita
        $solicitud = SolicitudVisita::create([
            'persona_id' => $validated['personaId'],
            'propiedad_id' => $validated['propiedadId'],
            'fecha_visita' => $validated['fechaVisita'],
            'comentarios' => $validated['comentarios'],
        ]);
    
        return response()->json($solicitud, 201); // Retorna el código HTTP 201 (creado)
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */

     public function show($id)
    {
        // Encuentra la solicitud de visita por su ID
        $solicitud = SolicitudVisita::with('persona', 'propiedad')->findOrFail($id);

        // Formatear los datos para la respuesta
        $formattedSolicitud = [
            'id' => $solicitud->id,
            'persona' => $solicitud->persona->nombre,
            'propiedad' => $solicitud->propiedad->direccion,
            'fecha_visita' => $solicitud->fecha_visita,
            'comentarios' => $solicitud->comentarios,
        ];

        return response()->json($formattedSolicitud);
    }
    public function edit($id)
    {
        // Encuentra la solicitud de visita por su ID
        $solicitud = SolicitudVisita::findOrFail($id);

        return response()->json($solicitud);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'persona_id' => 'required|exists:personas,id',
            'propiedad_id' => 'required|exists:propiedades,id',
            'fecha_visita' => 'required|date',
            'comentarios' => 'nullable|string|max:255',
        ]);

        // Encuentra la solicitud de visita y actualiza sus datos
        $solicitud = SolicitudVisita::findOrFail($id);
        $solicitud->update($validated);

        return response()->json($solicitud);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Encuentra la solicitud de visita y la elimina
        $solicitud = SolicitudVisita::findOrFail($id);
        $solicitud->delete();

        return response()->json(['message' => 'Solicitud de visita eliminada correctamente']);
    }
}
