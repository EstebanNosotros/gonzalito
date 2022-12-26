<?php
namespace Modules\Vendedores\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Vendedores\Models\Vendedor;

class VendedorFactory extends Factory
{
    protected $model = Vendedor::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name()
        ];
    }
}