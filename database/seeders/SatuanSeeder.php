<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Str;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $listSatuan = [
            ['nama' => 'Orang', 'warna' => $faker->hexColor()],
            ['nama' => 'Rupiah', 'warna' => $faker->hexColor()],
            ['nama' => '(%) Persen', 'warna' => $faker->hexColor()],
            ['nama' => 'Unit', 'warna' => $faker->hexColor()],
            ['nama' => 'Daerah', 'warna' => $faker->hexColor()],
            ['nama' => 'KM', 'warna' => $faker->hexColor()],
            ['nama' => 'Dokumen', 'warna' => $faker->hexColor()],
        ];

        foreach ($listSatuan as $key => $value) {
            $nama = $value['nama'];
            $warna = $value['warna'];

            $Satuan[$key] = Satuan::whereSlug(Str::slug($nama))->first();

            if (!$Satuan[$key]) {
                $Satuan[$key] = new Satuan();
                $Satuan[$key]->nama = $nama;
                $Satuan[$key]->warna = $warna;
                $Satuan[$key]->save();
            }
        }
    }
}
