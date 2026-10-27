<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;      // Pastikan ini menunjuk ke model User Anda
use App\Models\Role;      // Pastikan ini menunjuk ke model Role Anda
use App\Models\AreaKerja; // Pastikan ini menunjuk ke model AreaKerja Anda
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // --- 1. Dapatkan Role IDs yang Sudah Ada (ASUMSI SUDAH DI-SEED OLEH ROLES TABLE SEEDER) ---
        $adminRole = Role::where('name', 'Admin')->first();
        $vpKamtibRole = Role::where('name', 'VP Kamtib')->first();
        $vpOperasi1Role = Role::where('name', 'SVP Operasi 1')->first();



        // Validasi jika ada role yang tidak ditemukan
        if (!$adminRole || !$vpKamtibRole || !$vpOperasi1Role) {
            $this->command->error('Beberapa role penting (Admin, VP Kamtib, VP Operasi 1) tidak ditemukan. Pastikan RolesTableSeeder sudah dijalankan DULU!');
            return; // Hentikan eksekusi seeder ini jika role tidak ada
        }

        // --- 2. Dapatkan Area Kerja IDs yang Sudah Ada (ASUMSI SUDAH DI-SEED) ---
        $kantorArea = AreaKerja::where('nama', 'Kantor')->first();
        $pabrikArea = AreaKerja::where('nama', 'Pabrik')->first();

        if (!$kantorArea || !$pabrikArea) {
            $this->command->error('Beberapa Area Kerja penting (Kantor, Pabrik) tidak ditemukan. Pastikan AreaKerjaSeeder sudah dijalankan DULU!');
            return;
        }


        // --- 3. Buat Akun Admin Utama ---
        User::firstOrCreate(
            ['username' => 'admin_sistem'],
            [
                'password' => Hash::make('admin123'), // GANTI password ini dengan yang kuat!
                'role_id' => $adminRole->id,
                'area_kerja_id' => null, // Admin tidak terikat ke Area Kerja tertentu
            ]
        );
        $this->command->info('Akun Admin Utama (admin_sistem) berhasil dibuat/diperbarui.');

        // --- 4. Buat Akun User Approval (VP Kamtib - Role ID 2) ---
        User::firstOrCreate(
            ['username' => 'vpkamtib'],
            [
                'password' => Hash::make('vpkamtib123'), // GANTI password ini dengan yang kuat!
                'role_id' => $vpKamtibRole->id,
                'area_kerja_id' => $kantorArea->id, // Kaitkan dengan Area Kerja 'Kantor'
            ]
        );
        $this->command->info('Akun VP Kamtib (vpkamtib) berhasil dibuat/diperbarui dengan Area Kerja: ' . $kantorArea->nama);


        // --- 5. Buat Akun User Approval (VP Operasi 1 - Role ID 3) ---
        User::firstOrCreate(
            ['username' => 'vpoperasi1'],
            [
                'password' => Hash::make('vpoperasi123'), // GANTI password ini dengan yang kuat!
                'role_id' => $vpOperasi1Role->id,
                'area_kerja_id' => $pabrikArea->id, // Kaitkan dengan Area Kerja 'Pabrik'
            ]
        );
        $this->command->info('Akun VP Operasi 1 (vpoperasi1) berhasil dibuat/diperbarui dengan Area Kerja: ' . $pabrikArea->nama);
    }
}
