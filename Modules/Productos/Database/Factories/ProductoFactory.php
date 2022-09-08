<?php
namespace Modules\Productos\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Productos\Models\Producto;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name()
        ];
    }
}