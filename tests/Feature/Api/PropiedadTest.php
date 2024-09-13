<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Propiedad;

class PropiedadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * La clave de API que se usará en las pruebas.
     *
     * @var string
     */
    protected $apiKey = '35688d5f24fbf3d2c48275e588fc86405e0554ee';

    public function test_get_propiedades()
    {
        // Arrange: Crear datos de prueba
        Propiedad::factory()->count(5)->create();

        // Act: Hacer la solicitud al endpoint con API key
        $response = $this->withHeaders([
            'X-RapidAPI-Key' => $this->apiKey,
        ])->get('/api/propiedades');

        // Assert: Verificar la respuesta
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'direccion', 'ciudad', 'descripcion', 'precio'],
                 ]);
    }

    public function test_post_propiedad()
    {
        // Arrange: Datos de prueba
        $data = [
            'direccion' => 'Calle Falsa 123',
            'ciudad' => 'Ciudad Imaginaria',
            'descripcion' => 'Propiedad de prueba',
            'precio' => 1000000
        ];

        // Act: Hacer la solicitud al endpoint con API key
        $response = $this->withHeaders([
            'X-RapidAPI-Key' => $this->apiKey,
        ])->post('/api/propiedades', $data);

        // Assert: Verificar la respuesta
        $response->assertStatus(201)
                 ->assertJsonFragment($data);
    }

    public function test_put_propiedad()
    {
        // Arrange: Crear una propiedad existente
        $propiedad = Propiedad::factory()->create();
        $data = [
            'direccion' => 'Calle Actualizada 456',
            'ciudad' => 'Ciudad Actualizada',
            'descripcion' => 'Propiedad actualizada',
            'precio' => 1500000
        ];

        // Act: Hacer la solicitud al endpoint con API key
        $response = $this->withHeaders([
            'X-RapidAPI-Key' => $this->apiKey,
        ])->put("/api/propiedades/{$propiedad->id}", $data);

        // Assert: Verificar la respuesta
        $response->assertStatus(200)
                 ->assertJsonFragment($data);
    }

    public function test_delete_propiedad()
    {
        // Arrange: Crear una propiedad existente
        $propiedad = Propiedad::factory()->create();

        // Act: Hacer la solicitud al endpoint con API key
        $response = $this->withHeaders([
            'X-RapidAPI-Key' => $this->apiKey,
        ])->delete("/api/propiedades/{$propiedad->id}");

        // Assert: Verificar la respuesta
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Propiedad eliminada']);
    }
}
