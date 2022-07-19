<?php

namespace Database\Seeders;

use App\Models\JenisUnit;
use Illuminate\Database\Seeder;
use Str;
use Faker\Factory as Faker;

class JenisUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $listJenisUnit = [
            ['nama' => 'Badan', 'warna' => $faker->hexColor()],
            ['nama' => 'Dinas', 'warna' => $faker->hexColor()],
            ['nama' => 'Kecamatan', 'warna' => $faker->hexColor()],
            ['nama' => 'Kelurahan', 'warna' => $faker->hexColor()],
            ['nama' => 'Rumah Sakit', 'warna' => $faker->hexColor()],
            ['nama' => 'SD', 'warna' => $faker->hexColor()],
            ['nama' => 'Sekretariat', 'warna' => $faker->hexColor()],
            ['nama' => 'SLB', 'warna' => $faker->hexColor()],
            ['nama' => 'SMP', 'warna' => $faker->hexColor()],
            ['nama' => 'TK', 'warna' => $faker->hexColor()],
            ['nama' => 'UPTD', 'warna' => $faker->hexColor()],
            ['nama' => 'Vertikal', 'warna' => $faker->hexColor()],
        ];

        foreach ($listJenisUnit as $key => $value) {
            $nama = $value['nama'];
            $warna = $value['warna'];

            $JenisUnit[$key] = JenisUnit::whereSlug(Str::slug($nama))->first();

            if (!$JenisUnit[$key]) {
                $JenisUnit[$key] = new JenisUnit();
                $JenisUnit[$key]->nama = $nama;
                $JenisUnit[$key]->warna = $warna;
                $JenisUnit[$key]->save();
            }
        }
    }
}
