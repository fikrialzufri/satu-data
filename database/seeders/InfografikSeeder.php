<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\Infografik;
use App\Models\KategoriInfografik;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InfografikSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $kategoriInfografik = KategoriInfografik::factory(50)->create();

        for ($i = 0; $i < 10; $i++) {
            $galleries = Gallery::factory(10)->create();
            
            $firstGallery = $galleries->first();
            
            $infografik = Infografik::create([
                'judul' => $faker->sentence(4),
                'kategori_infografik_id' => $kategoriInfografik->random()->id,
                'thumbnail_id' => $firstGallery->id,
                'isi_infografik' => $faker->paragraphs(3, true),
            ]);

            $infografik->hasGallery()->attach($galleries->pluck('id'));
        }
    }
}
