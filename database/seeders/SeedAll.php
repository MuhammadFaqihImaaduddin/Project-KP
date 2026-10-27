<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeedAll extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->call([
            AreaKerjaSeeder::class,
            RolesTableSeeder::class,
            StatusesSeeder::class,
            superAdminSeeder::class,
        ]);
    }
}
