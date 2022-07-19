<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeedeer::class,
            JenisDataSeeder::class,
            GroupSeeder::class,
            JenisUnitSeeder::class,
            LegendaSeeder::class,
            SatuanSeeder::class,
        ]);
    }
}
