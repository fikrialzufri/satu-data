<?php

namespace Database\Seeders;

use App\Models\Legenda;
use Illuminate\Database\Seeder;
use Str;
use Faker\Factory as Faker;

class LegendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $listLegenda = [
            ['nama' => 'Aram', 'warna' => $faker->hexColor()],
            ['nama' => 'Proyeksi | n/a Tidak Ada', 'warna' => $faker->hexColor()],
            ['nama' => 'Sangat Sementara', 'warna' => $faker->hexColor()],
            ['nama' => 'Sementara', 'warna' => $faker->hexColor()],
            ['nama' => 'Tetap', 'warna' => $faker->hexColor()]
        ];

        foreach ($listLegenda as $key => $value) {
            $nama = $value['nama'];
            $warna = $value['warna'];

            $Legenda[$key] = Legenda::whereSlug(Str::slug($nama))->first();

            if (!$Legenda[$key]) {
                $Legenda[$key] = new Legenda();
                $Legenda[$key]->nama = $nama;
                $Legenda[$key]->warna = $warna;
                $Legenda[$key]->save();
            }
        }
    }
}
