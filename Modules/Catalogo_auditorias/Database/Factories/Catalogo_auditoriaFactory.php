<?php
namespace Modules\Catalogo_auditorias\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Catalogo_auditorias\Models\Catalogo_auditoria;

class Catalogo_auditoriaFactory extends Factory
{
    protected $model = Catalogo_auditoria::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name()
        ];
    }
}