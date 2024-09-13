<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Propiedad;

class PropiedadFactory extends Factory
{
    /**
     * El nombre del modelo que la fábrica está creando.
     *
     * @var string
     */
    protected $model = Propiedad::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'direccion' => $this->faker->address,      // Genera una dirección de prueba
            'ciudad' => $this->faker->city,            // Genera una ciudad de prueba
            'descripcion' => $this->faker->sentence,   // Genera una descripción de prueba
            'precio' => $this->faker->numberBetween(100000, 1000000), // Genera un precio entre 100,000 y 1,000,000
        ];
    }
}
