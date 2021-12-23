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
            AdminRoleSeeder::class,
            UserRoleSeeder::class,
            CategorySeeder::class,
            LocationSeeder::class,
            VendorSeeder::class
        ]);
    }
}
