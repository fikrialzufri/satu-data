<?php

namespace Database\Factories;

use App\Models\KategoriInfografik;
use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriInfografikFactory extends Factory
{
    protected $model = KategoriInfografik::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->words(2, true),
        ];
    }
}
