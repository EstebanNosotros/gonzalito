<?php
namespace Modules\Avisos\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Avisos\Models\Aviso;

class AvisoFactory extends Factory
{
    protected $model = Aviso::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name()
        ];
    }
}