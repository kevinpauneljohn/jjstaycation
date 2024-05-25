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
        // \App\Models\Customer::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PhilippineRegionsTableSeeder::class,
            PhilippineProvincesTableSeeder::class,
            PhilippineCitiesTableSeeder::class,
            PhilippineBarangaysTableSeeder::class
        ]);
    }
}
