<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AreaKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('area_kerja')->insert([
            ['nama' => 'Kantor'],
            ['nama' => 'Pabrik'],
            // Add more records as needed
        ]);
    }
}
