<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Str;
use Faker\Factory as Faker;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $listGroup = [
            [
                'nama' => 'PENDIDIKAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KESEHATAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'PEKERJAANN UMUM', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'PERUMAHAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'PENATAAN	RUANG', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'BAPPEDA', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'PERHUBUNGAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'LINGKUNGAN HIDUP', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'PERTANAHAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KEPENDUDUKAN DAN CATATAN SIPIL', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'SOSIAL', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KETENAGAKERJAAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KOPERASI DAN UMKM', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'PENANAMAN MODAL', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KEBUDAYAAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KEPEMUDAAN DAN OLAHRAGA', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KESATUAN BANGSA DAN POLIYIK DALAM NEGERI', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'OTONOMI DAERAH, PEMERINTAHAN UMUM, ADMINISTRASI, KEUANGAN DAERAH, PERANGKAT DAERAH, KEPEGAWAIAN DAN PERSANDIAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KETAHANAN PANGAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'PEMBERDAYAAN MASYARAKAT', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'STATISTIK', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KEARSIPAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KOMUNIKASI DAN INFORMATIKA', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'PERPUSTAKAAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'PERTANIAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KEHUTANAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'ENERGI DAN SUMBER DAYA MINERAL', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'PARIWISATA', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KELAUTAN DAN PERIKANAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'PERDAGANGAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'INDUSTRI', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KETRANSMIGRASIAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'KEISTIMEWAAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'DATA VERTIKAL KANWIL KEMENAG', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'DATA VERTIKAL BADAN PUSAT STATISTIK', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'DATA VERTIKAL BADAN PUSAT PERTAHANAN NASIONAL', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'DATA VERTIKAL BADAN KOORDINASI KELUARGA BERENCANA NASIONAL', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'DATA VERTIKAL BANK INDONESIA', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'DATA VERTIKAL OJK', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'DATA VERTIKAL KEPOLISIAN REPUBLIK INDONESIA DAERAH', 'warna' => $faker->hexColor()
            ],

            [
                'nama' => 'KETENTRAMAN', 'warna' => $faker->hexColor()
            ],
            [
                'nama' => 'PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA', 'warna' => $faker->hexColor()
            ],
        ];

        foreach ($listGroup as $key => $value) {
            $nama = $value['nama'];
            $warna = $value['warna'];

            $Group[$key] = Group::whereSlug(Str::slug($nama))->first();

            if (!$Group[$key]) {
                $Group[$key] = new Group();
                $Group[$key]->nama = $nama;
                $Group[$key]->warna = $warna;
                $Group[$key]->save();
            }
        }
    }
}
