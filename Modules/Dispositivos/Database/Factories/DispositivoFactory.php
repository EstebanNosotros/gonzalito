<?php
namespace Modules\Dispositivos\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Dispositivos\Models\Dispositivo;

class DispositivoFactory extends Factory
{
    protected $model = Dispositivo::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name()
        ];
    }
}