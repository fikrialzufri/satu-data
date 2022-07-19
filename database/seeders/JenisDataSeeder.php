<?php

namespace Database\Seeders;

use App\Models\JenisData;
use Illuminate\Database\Seeder;
use Str;
use Faker\Factory as Faker;

class JenisDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $listJenisData = [
            [
                'nama' => 'DATA DASAR', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'DATA UTAMA', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'Gambaran Umum', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'Kelurahan', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'IKK	', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'IKU', 'warna' => $faker->hexColor()
            ],
            ['nama' => 'SDGS', 'warna' => $faker->hexColor()]
        ];

        foreach ($listJenisData as $key => $value) {
            $nama = $value['nama'];
            $warna = $value['warna'];

            $JenisData[$key] = JenisData::whereSlug(Str::slug($nama))->first();

            if (!$JenisData[$key]) {
                $JenisData[$key] = new JenisData();
                $JenisData[$key]->nama = $nama;
                $JenisData[$key]->warna = $warna;
                $JenisData[$key]->save();
            }
        }
    }
}
