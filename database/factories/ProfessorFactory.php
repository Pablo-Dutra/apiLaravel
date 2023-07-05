<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class ProfessorFactory extends Factory
{
    public function definition(): array
    {
        $titulos = ['Graducação','Especialização','Mestrado','Doutorado'];
        return [
            'nome'=>fake()->name,
            'titulacao'=>$titulos[rand(0,3)]
        ];
    }
}
