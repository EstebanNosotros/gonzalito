<?php
namespace Modules\Banners\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Banners\Models\Banner;

class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name()
        ];
    }
}