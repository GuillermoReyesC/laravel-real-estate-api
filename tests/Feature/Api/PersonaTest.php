<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Persona;
use Tests\TestCase;

class PersonaTest extends TestCase
{
    use RefreshDatabase;

    protected $apiKey = '35688d5f24fbf3d2c48275e588fc86405e0554ee';
    protected $persona;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Configurar datos de prueba iniciales
        $this->persona = \App\Models\Persona::factory()->create();
    }

    public function test_get_personas()
    {
        // Arrange: Crear datos de prueba
        Persona::factory()->count(5)->create();

        // Act: Hacer la solicitud al endpoint con API key
        $response = $this->withHeaders([
            'X-RapidAPI-Key' =>  $this->apiKey,
        ])->get('/api/personas');

        // Assert: Verificar la respuesta
        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => ['id', 'nombre', 'email', 'telefono'],
                ]);
    }

    public function test_post_persona()
    {
        // Arrange: Datos de prueba
        $data = ['nombre' => 'Juan Pérez', 'email' => 'juan@example.com', 'telefono' => '123456789'];

        // Act: Hacer la solicitud al endpoint con API key
        $response = $this->withHeaders([
            'X-RapidAPI-Key' =>  $this->apiKey,
        ])->post('/api/personas', $data);

        // Assert: Verificar la respuesta
        $response->assertStatus(201)
                ->assertJsonFragment($data);
    }

    public function test_put_persona()
    {
        // Arrange: Crear un persona existente
        $persona = Persona::factory()->create();
        $data = ['nombre' => 'Juan Pérez Actualizado', 'email' => 'juan.updated@example.com', 'telefono' => '987654321'];

        // Act: Hacer la solicitud al endpoint con API key
        $response = $this->withHeaders([
            'X-RapidAPI-Key' =>  $this->apiKey,
        ])->put("/api/personas/{$persona->id}", $data);

        // Assert: Verificar la respuesta
        $response->assertStatus(200)
                ->assertJsonFragment($data);
    }

    public function test_delete_persona()
    {
        // Arrange: Crear un persona existente
        $persona = Persona::factory()->create();

        // Act: Hacer la solicitud al endpoint con API key
        $response = $this->withHeaders([
            'X-RapidAPI-Key' =>  $this->apiKey,
        ])->delete("/api/personas/{$persona->id}");

        // Assert: Verificar la respuesta
        $response->assertStatus(200);
    }
}
