<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\JenisData;
use App\Models\JenisUnit;
use App\Models\Satuan;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UnitElementSubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $user = User::query()->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Seeder User',
                'username' => 'seeder-user',
                'slug' => 'seeder-user',
                'email' => 'seeder@example.test',
                'password' => Hash::make('password'),
            ]);
        }

        $jenisUnitIds = JenisUnit::pluck('id')->all();
        $groupIds = Group::pluck('id')->all();
        $jenisDataIds = JenisData::pluck('id')->all();
        $satuanIds = Satuan::pluck('id')->all();

        if (
            empty($jenisUnitIds) ||
            empty($groupIds) ||
            empty($jenisDataIds) ||
            empty($satuanIds)
        ) {
            if ($this->command) {
                $this->command->warn('Seeder master data belum dijalankan. Jalankan seeder master sebelum seeder ini.');
            }
            return;
        }

        $unitTarget = 20;
        $elementPerUnit = 20;
        $subPerElement = 20;
        $timestamp = now();

        $existingUnits = DB::table('unit')->count();
        $unitsPayload = [];
        $unitReferences = [];

        for ($i = 1; $i <= $unitTarget; $i++) {
            $sequence = $existingUnits + $i;
            $unitId = (string) Str::uuid();
            $unitName = "Unit Sample {$sequence}";

            $unitsPayload[] = [
                'id' => $unitId,
                'nama' => $unitName,
                'slug' => Str::slug($unitName),
                'nama_singkat' => 'US' . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT),
                'lat_long' => $faker->latitude . ',' . $faker->longitude,
                'logo' => null,
                'email' => "unit{$sequence}@example.test",
                'telepon' => $faker->numerify('08###########'),
                'alamat' => $faker->address(),
                'detail_alamat' => $faker->address(),
                'keterangan' => $faker->sentence(10),
                'jenis_unit_id' => $faker->randomElement($jenisUnitIds),
                'tampil' => 'Y',
                'akun' => 'Y',
                'setuju' => 'Y',
                'user_id' => $user->id,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];

            $unitReferences[] = [
                'id' => $unitId,
                'sequence' => $sequence,
            ];
        }

        DB::table('unit')->insert($unitsPayload);

        $elementCounter = DB::table('element')->count();
        $elementsBatch = [];
        $elementReferences = [];
        $elementBatchSize = 1000;

        foreach ($unitReferences as $unitReference) {
            for ($elementIndex = 1; $elementIndex <= $elementPerUnit; $elementIndex++) {
                $elementCounter++;
                $elementId = (string) Str::uuid();
                $elementName = "Element {$unitReference['sequence']}-{$elementIndex}";

                $elementsBatch[] = [
                    'id' => $elementId,
                    'kode' => str_pad((string) $elementCounter, 5, '0', STR_PAD_LEFT),
                    'slug' => Str::slug($elementName) . '-' . $elementCounter,
                    'nama' => $elementName,
                    'keterangan' => $faker->sentence(12),
                    'dokumentasi' => $faker->sentence(8),
                    'setuju' => 'Y',
                    'group_id' => $faker->randomElement($groupIds),
                    'unit_id' => $unitReference['id'],
                    'jenis_data_id' => $faker->randomElement($jenisDataIds),
                    'user_id' => $user->id,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];

                $elementReferences[] = [
                    'id' => $elementId,
                    'sequence' => $elementCounter,
                ];

                if (count($elementsBatch) === $elementBatchSize) {
                    DB::table('element')->insert($elementsBatch);
                    $elementsBatch = [];
                }
            }
        }

        if (!empty($elementsBatch)) {
            DB::table('element')->insert($elementsBatch);
        }

        $subElementCounter = DB::table('sub_element')->count();
        $subElementsBatch = [];
        $subBatchSize = 2000;

        foreach ($elementReferences as $elementReference) {
            for ($subIndex = 1; $subIndex <= $subPerElement; $subIndex++) {
                $subElementCounter++;
                $subElementId = (string) Str::uuid();
                $subName = "Sub Element {$elementReference['sequence']}-{$subIndex}";

                $subElementsBatch[] = [
                    'id' => $subElementId,
                    'kode' => 'SE' . str_pad((string) $subElementCounter, 6, '0', STR_PAD_LEFT),
                    'nama' => $subName,
                    'slug' => Str::slug($subName) . '-' . $subElementCounter,
                    'keterangan' => $faker->sentence(12),
                    'sumber_data' => $faker->company(),
                    'metode_perhitungan' => $faker->sentence(10),
                    'parent' => 'N',
                    'meta_data' => null,
                    'satuan_id' => $faker->randomElement($satuanIds),
                    'element_id' => $elementReference['id'],
                    'user_id' => $user->id,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];

                if (count($subElementsBatch) === $subBatchSize) {
                    DB::table('sub_element')->insert($subElementsBatch);
                    $subElementsBatch = [];
                }
            }
        }

        if (!empty($subElementsBatch)) {
            DB::table('sub_element')->insert($subElementsBatch);
        }
    }
}
