<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\SolicitudVisita;
use App\Models\Persona;
use App\Models\Propiedad;

class SolicitudVisitaTest extends TestCase
{
    use RefreshDatabase;

    protected $apiKey = '35688d5f24fbf3d2c48275e588fc86405e0554ee';

    public function test_get_solicitudes_visitas()
    {
        // Arrange: Crear datos de prueba
        SolicitudVisita::factory()->count(5)->create();

        // Act: Hacer la solicitud al endpoint con API key
        $response = $this->withHeaders([
            'X-RapidAPI-Key' => $this->apiKey,
        ])->get('/api/solicitudes-visitas');

        // Assert: Verificar la respuesta
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'id',
                         'persona',
                         'propiedad',
                         'fecha_visita',
                         'comentarios'
                     ],
                 ]);
    }

    public function test_post_solicitud_visita()
    {
        // Arrange: Crear datos de prueba para Persona y Propiedad
        $persona = Persona::factory()->create();
        $propiedad = Propiedad::factory()->create();

        $data = [
            'personaId' => $persona->id,
            'propiedadId' => $propiedad->id,
            'fechaVisita' => '2024-09-30',
            'comentarios' => 'Visita de prueba',
        ];

        // Act: Hacer la solicitud al endpoint con API key
        $response = $this->withHeaders([
            'X-RapidAPI-Key' => $this->apiKey,
        ])->post('/api/solicitudes-visitas', $data);

        // Assert: Verificar la respuesta
        $response->assertStatus(201)
                 ->assertJsonFragment($data);
    }

    public function test_put_solicitud_visita()
    {
        // Arrange: Crear una solicitud de visita existente
        $solicitud = SolicitudVisita::factory()->create();
        $data = [
            'persona_id' => Persona::factory()->create()->id,
            'propiedad_id' => Propiedad::factory()->create()->id,
            'fecha_visita' => '2024-10-01',
            'comentarios' => 'Visita actualizada',
        ];

        // Act: Hacer la solicitud al endpoint con API key
        $response = $this->withHeaders([
            'X-RapidAPI-Key' => $this->apiKey,
        ])->put("/api/solicitudes-visitas/{$solicitud->id}", $data);

        // Assert: Verificar la respuesta
        $response->assertStatus(200)
                 ->assertJsonFragment($data);
    }

    public function test_delete_solicitud_visita()
    {
        // Arrange: Crear una solicitud de visita existente
        $solicitud = SolicitudVisita::factory()->create();

        // Act: Hacer la solicitud al endpoint con API key
        $response = $this->withHeaders([
            'X-RapidAPI-Key' => $this->apiKey,
        ])->delete("/api/solicitudes-visitas/{$solicitud->id}");

        // Assert: Verificar la respuesta
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Solicitud de visita eliminada correctamente']);
    }
}
