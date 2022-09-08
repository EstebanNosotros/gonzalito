<?php
namespace Modules\Categorias\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Categorias\Models\Categoria;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name()
        ];
    }
}