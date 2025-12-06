<?php

namespace Database\Factories;

use App\Models\Infografik;
use Illuminate\Database\Eloquent\Factories\Factory;

class InfografikFactory extends Factory
{
    protected $model = Infografik::class;

    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence(4),
            'kategori_infografik_id' => null,
            'thumbnail_id' => null,
            'isi_infografik' => $this->faker->paragraphs(3, true),
        ];
    }
}
