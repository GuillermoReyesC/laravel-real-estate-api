<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SolicitudVisitaFactory extends Factory
{
    /**
     * El nombre del modelo que la fábrica representa.
     *
     * @var string
     */
    protected $model = SolicitudVisita::class;

    /**
     * Define el estado predeterminado del modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'persona_id' => Persona::factory(), // Suponiendo que tienes un factory para Persona
            'propiedad_id' => Propiedad::factory(), // Suponiendo que tienes un factory para Propiedad
            'fecha_visita' => $this->faker->date(), // Fecha de la visita generada aleatoriamente
            'comentarios' => $this->faker->optional()->text(255), // Comentarios opcionales
        ];
    }
}
